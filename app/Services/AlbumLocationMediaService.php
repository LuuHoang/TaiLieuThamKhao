<?php

namespace App\Services;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Models\AlbumLocationMediaModel;
use App\Models\AlbumLocationModel;
use App\Models\MediaPropertyModel;
use App\Repositories\Criteria\FilterBetweenDayCriteria;
use App\Repositories\Repository;
use Illuminate\Support\Arr;

class AlbumLocationMediaService extends AbstractService
{
    protected $_albumLocationMediaRepository;
    protected $_dataUsageStatisticService;
    protected $_mediaPropertyRepository;
    protected $_validationService;
    protected $_commonService;

    public function __construct(
        AlbumLocationMediaModel $albumLocationMediaModel,
        DataUsageStatisticService $dataUsageStatisticService,
        MediaPropertyModel $mediaPropertyModel,
        ValidationService $validationService,
        CommonService $commonService
    )
    {
        $this->_albumLocationMediaRepository = new Repository($albumLocationMediaModel);
        $this->_dataUsageStatisticService = $dataUsageStatisticService;
        $this->_mediaPropertyRepository = new Repository($mediaPropertyModel);
        $this->_validationService = $validationService;
        $this->_commonService = $commonService;
    }

    public function removeAlbumLocationMedia(int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $currentUser = app('currentUser');
        $albumLocationMediaEntity = $this->_albumLocationMediaRepository
            ->where('album_location_id', '=', $albumLocationId)
            ->find($albumLocationMediaId);
        if ($albumLocationMediaEntity == null || $albumLocationMediaEntity->albumLocation->album_id != $albumId) {
            throw new NotFoundException('messages.media_does_not_exist');
        }

        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumLocationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        try {
            $album = $albumLocationMediaEntity->albumLocation->album;
            $albumLocationMediaEntity->delete();
            $this->_dataUsageStatisticService->updateAlbumSize($album, (-1) * $albumLocationMediaEntity->size);
            $this->_dataUsageStatisticService->updateCountDataUserUsage($album->user->userUsage, (-1) * $albumLocationMediaEntity->size);
            $this->_dataUsageStatisticService->updateCountDataCompanyUsage($album->user->company->companyUsage, (-1) * $albumLocationMediaEntity->size);
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function checkValidateInsertLocationMediaInformation(Array $locationMediaInformationData, int $companyId)
    {
        $currentUser = app('currentUser');
        $locationMediaPropertyDataIds = Arr::pluck($locationMediaInformationData, 'media_property_id');
        $mediaPropertyEntities = $this->_mediaPropertyRepository
            ->where('company_id', "=", $companyId)
            ->all();

        $locationMediaPropertyIds = $mediaPropertyEntities->pluck('id')->all();

        if (count($locationMediaPropertyDataIds) != count(array_unique($locationMediaPropertyDataIds))) {
            throw new UnprocessableException('messages.media_information_is_incorrect');
        }

        foreach ($locationMediaInformationData as $mediaInformation) {
            if (!empty($mediaInformation['value'])) {
                $mediaPropertyEntity = $mediaPropertyEntities->firstWhere('id', $mediaInformation["media_property_id"]);
                $this->_validationService->checkValidateInputType($mediaPropertyEntity->title, $mediaInformation["value"], $mediaPropertyEntity->type, $currentUser->userSetting->language ?? config('app.locale'));
            }
        }

        foreach ($locationMediaPropertyDataIds as $locationMediaPropertyDataId) {
            if (!in_array($locationMediaPropertyDataId, $locationMediaPropertyIds)) {
                throw new NotFoundException('messages.media_property_id_is_incorrect');
            }
        }
    }

    public function checkValidateUpdateOrInsertLocationMediaInformation(Array $locationMediaInformationData, int $companyId)
    {
        $currentUser = app('currentUser');
        $mediaPropertyDataIds = Arr::pluck($locationMediaInformationData, 'media_property_id');
        $mediaPropertyEntities = $this->_mediaPropertyRepository
            ->where('company_id', "=", $companyId)
            ->all();

        $mediaPropertyIds = $mediaPropertyEntities->pluck('id')->all();

        if (count($mediaPropertyDataIds) != count(array_unique($mediaPropertyDataIds))) {
            throw new UnprocessableException('messages.media_information_is_incorrect');
        }

        foreach ($locationMediaInformationData as $mediaInformation) {
            if (!empty($mediaInformation['value'])) {
                $mediaPropertyEntity = $mediaPropertyEntities->firstWhere('id', $mediaInformation["media_property_id"]);
                $this->_validationService->checkValidateInputType($mediaPropertyEntity->title, $mediaInformation["value"], $mediaPropertyEntity->type, $currentUser->userSetting->language ?? config('app.locale'));
            }
        }

        foreach ($mediaPropertyDataIds as $mediaPropertyDataId) {
            if (!in_array($mediaPropertyDataId, $mediaPropertyIds)) {
                throw new NotFoundException('messages.media_property_id_is_incorrect');
            }
        }
    }

    public function updateAlbumLocationMedias(AlbumLocationModel $albumLocationEntity, Array $albumLocationMediaData)
    {
        try {
            $this->beginTransaction();

            foreach ($albumLocationMediaData as $albumLocationMedia) {
                $subData = [];
                if (isset($albumLocationMedia['description'])) {
                    $subData['description'] = $albumLocationMedia['description'];
                }
                if (isset($albumLocationMedia['created_time'])) {
                    $subData['created_time'] = $albumLocationMedia['created_time'];
                }
                if (!empty($subData)) {
                    $albumLocationMediaEntity = tap($albumLocationEntity->albumLocationMedias()->find($albumLocationMedia['id']))->update($subData);
                }
                if (isset($albumLocationMedia['information']) && !empty($albumLocationMedia['information'])) {
                    foreach ($albumLocationMedia['information'] as $information) {
                        $albumLocationMediaEntity->mediaInformation()->updateOrCreate(
                            ['media_property_id' => $information['media_property_id']],
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

    public function getListMediasByBetweenDays(array $locationIds, array $paramQuery)
    {
        return $this->_albumLocationMediaRepository->pushCriteria(new FilterBetweenDayCriteria($paramQuery))->whereIn('album_location_id', $locationIds)->all();
    }
}
