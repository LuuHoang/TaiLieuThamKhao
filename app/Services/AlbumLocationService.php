<?php

namespace App\Services;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Models\AlbumLocationMediaModel;
use App\Models\AlbumLocationModel;
use App\Models\AlbumModel;
use App\Models\LocationPropertyModel;
use App\Repositories\Criteria\FilterBetweenDayCriteria;
use App\Repositories\Repository;
use Illuminate\Support\Arr;

class AlbumLocationService extends AbstractService
{
    protected $_albumRepository;
    protected $_albumLocationRepository;
    protected $_albumLocationMediaRepository;
    protected $_dataUsageStatisticService;
    protected $_locationPropertyRepository;
    protected $_albumLocationMediaService;
    protected $_validationService;
    protected $_commonService;

    public function __construct (
        AlbumLocationModel $albumLocationModel,
        AlbumLocationMediaModel $albumLocationMediaModel,
        AlbumModel $albumModel,
        DataUsageStatisticService $dataUsageStatisticService,
        LocationPropertyModel $locationPropertyModel,
        AlbumLocationMediaService $albumLocationMediaService,
        ValidationService $validationService,
        CommonService $commonService
    )
    {
        $this->_albumRepository = new Repository($albumModel);
        $this->_albumLocationRepository = new Repository($albumLocationModel);
        $this->_albumLocationMediaRepository = new Repository($albumLocationMediaModel);
        $this->_dataUsageStatisticService = $dataUsageStatisticService;
        $this->_locationPropertyRepository = new Repository($locationPropertyModel);
        $this->_albumLocationMediaService = $albumLocationMediaService;
        $this->_validationService = $validationService;
        $this->_commonService = $commonService;
    }

    public function removeAlbumLocation(int $albumId, int $albumLocationId)
    {
        $currentUser = app('currentUser');
        $albumLocationEntity = $this->_albumLocationRepository
            ->with(['album', 'albumLocationMedias'])
            ->where('album_id', '=', $albumId)
            ->find($albumLocationId);
        if ($albumLocationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }
        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumLocationEntity->album)) {
            throw new ForbiddenException('messages.not_have_permission');
        }
        try {
            $this->beginTransaction();
            $album = $albumLocationEntity->album;
            $totalSizeMediaAlbum = $albumLocationEntity->albumLocationMedias->sum('size');
            $albumLocationEntity->delete();
            $this->_dataUsageStatisticService->updateAlbumSize($album, (-1) * $totalSizeMediaAlbum);
            $this->_dataUsageStatisticService->updateCountDataUserUsage($album->user->userUsage, (-1) * $totalSizeMediaAlbum);
            $this->_dataUsageStatisticService->updateCountDataCompanyUsage($album->user->company->companyUsage, (-1) * $totalSizeMediaAlbum);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function insertAlbumLocations(AlbumModel $albumModel, Array $albumLocationData, int $companyId)
    {
        $albumLocationTitles = collect($albumLocationData)->pluck('title')->toArray();

        if (count(array_unique($albumLocationTitles)) != count($albumLocationTitles)) {
            throw new UnprocessableException('messages.album_location_title_is_incorrect');
        }

        foreach ($albumLocationData as $albumLocation) {
            if (isset($albumLocation['information']) && !empty($albumLocation['information'])) {
                $this->_validateInsertLocationInformation($albumLocation['information'], $companyId);
            }
        }

        try {
            $this->beginTransaction();

            foreach ($albumLocationData as $albumLocation) {
                $albumLocationEntity = $albumModel->albumLocations()->create([
                    'title'         => $albumLocation['title'],
                    'description'   => $albumLocation['description'] ?? ""
                ]);
                if (isset($albumLocation['information']) && !empty($albumLocation['information'])) {
                    $albumLocationEntity->locationInformation()->createMany($albumLocation['information']);
                }
            }

            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    protected function _validateInsertLocationInformation(Array $locationInformationData, int $companyId)
    {
        $currentUser = app('currentUser');
        $locationPropertyDataIds = Arr::pluck($locationInformationData, 'location_property_id');
        $locationPropertyEntities = $this->_locationPropertyRepository
            ->where('company_id', "=", $companyId)
            ->all();

        $locationPropertyIds = $locationPropertyEntities->pluck('id')->all();

        if (count($locationPropertyDataIds) != count(array_unique($locationPropertyDataIds))) {
            throw new UnprocessableException('messages.location_information_is_incorrect');
        }

        foreach ($locationInformationData as $locationInformation) {
            if (!empty($locationInformation['value'])) {
                $locationPropertyEntity = $locationPropertyEntities->firstWhere('id', $locationInformation["location_property_id"]);
                $this->_validationService->checkValidateInputType($locationPropertyEntity->title, $locationInformation["value"], $locationPropertyEntity->type, $currentUser->userSetting->language ?? config('app.locale'));
            }
        }

        foreach ($locationPropertyDataIds as $locationPropertyDataId) {
            if (!in_array($locationPropertyDataId, $locationPropertyIds)) {
                throw new NotFoundException('messages.location_property_id_is_incorrect');
            }
        }
    }

    public function updateOrInsertAlbumLocations (AlbumModel $albumModel, Array $albumLocationData, int $companyId)
    {
        $albumLocationDataUpdate = [];
        $albumLocationDataInsert = [];
        foreach ($albumLocationData as $locationData) {
            if (isset($locationData['id']) && !empty($locationData['id'])) {
                $albumLocationDataUpdate[] = $locationData;
            } else {
                $albumLocationDataInsert[] = $locationData;
            }
        }

        if (!empty($albumLocationDataInsert)) {
            $this->insertAlbumLocations($albumModel, $albumLocationDataInsert, $companyId);
        }

        if (!empty($albumLocationDataUpdate)) {
            $this->updateAlbumLocations($albumModel, $albumLocationDataUpdate, $companyId);
        }
    }

    public function updateAlbumLocations(AlbumModel $albumModel, Array $albumLocationData, int $companyId)
    {
        foreach ($albumLocationData as $albumLocation) {
            if (isset($albumLocation['information']) && !empty($albumLocation['information'])) {
                $this->_validateUpdateOrInsertAlbumLocationInformation($albumLocation['information'], $companyId);
            }
        }

        try {
            $this->beginTransaction();

            foreach ($albumLocationData as $albumLocation) {
                if (isset($albumLocation['description'])) {
                    $albumLocationEntity = tap($albumModel->albumLocations()->find($albumLocation['id']))->update([
                        'description'   => $albumLocation['description']
                    ]);
                }

                if (isset($albumLocation['information']) && !empty($albumLocation['information'])) {
                    foreach ($albumLocation['information'] as $information) {
                        $albumLocationEntity->locationInformation()->updateOrCreate(
                            ['location_property_id' => $information['location_property_id']],
                            $information
                        );
                    }
                }
            }

            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    protected function _validateUpdateOrInsertAlbumLocationInformation (Array $locationInformationData, int $companyId)
    {
        $currentUser = app('currentUser');
        $locationPropertyDataIds = Arr::pluck($locationInformationData, 'location_property_id');
        $locationPropertyEntities = $this->_locationPropertyRepository
            ->where('company_id', "=", $companyId)
            ->all();

        $locationPropertyIds = $locationPropertyEntities->pluck('id')->all();

        if (count($locationPropertyDataIds) != count(array_unique($locationPropertyDataIds))) {
            throw new UnprocessableException('messages.location_information_is_incorrect');
        }

        foreach ($locationInformationData as $locationInformation) {
            if (!empty($locationInformation['value'])) {
                $locationPropertyEntity = $locationPropertyEntities->firstWhere('id', $locationInformation["location_property_id"]);
                $this->_validationService->checkValidateInputType($locationPropertyEntity->title, $locationInformation["value"], $locationPropertyEntity->type, $currentUser->userSetting->language ?? config('app.locale'));
            }
        }

        foreach ($locationPropertyDataIds as $locationPropertyDataId) {
            if (!in_array($locationPropertyDataId, $locationPropertyIds)) {
                throw new NotFoundException('messages.location_property_id_is_incorrect');
            }
        }
    }

    public function getListLocationByBetweenDays(array $albumIds, array $paramQuery)
    {
        return $this->_albumLocationRepository->pushCriteria(new FilterBetweenDayCriteria($paramQuery))->whereIn('album_id', $albumIds)->all();
    }
}
