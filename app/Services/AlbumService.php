<?php

namespace App\Services;

use App\Constants\AlbumProperty;
use App\Constants\Boolean;
use App\Constants\InputType;
use App\Constants\WebCommunication;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Mail\ShareAlbum;
use App\Models\AlbumInformationModel;
use App\Models\AlbumLocationMediaModel;
use App\Models\AlbumLocationModel;
use App\Models\AlbumModel;
use App\Models\AlbumPropertyModel;
use App\Models\AlbumTypeModel;
use App\Models\CompanyUsageModel;
use App\Models\LocationPropertyModel;
use App\Models\LocationTypeModel;
use App\Models\PdfFileModel;
use App\Models\SharedAlbumModel;
use App\Models\UserModel;
use App\Repositories\Criteria\FilterBetweenDayCriteria;
use App\Repositories\Criteria\SearchAlbumsCriteria;
use App\Repositories\Repository;
use App\Services\WebService\TemplateService;
use App\Traits\GenerateShortCodeDataTrait;
use App\Utils\Util;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AlbumService extends AbstractService
{
    use GenerateShortCodeDataTrait;

    protected $_albumPropertyRepository;
    protected $_albumTypeRepository;
    protected $_locationTypeRepository;
    protected $_albumRepository;
    protected $_albumInformationRepository;
    protected $_albumLocationRepository;
    protected $_albumLocationMediaRepository;
    protected $_sharedAlbum;
    protected $_locationPropertyRepository;
    protected $_albumLocationService;
    protected $_userRepository;
    protected $_validationService;
    protected $_commonService;
    protected $templateService;
    protected $pdfFileRepository;
    protected $companyUsageRepository;

    public function __construct(
        AlbumPropertyModel $albumPropertyModel,
        AlbumTypeModel $albumTypeModel,
        LocationTypeModel $locationTypeModel,
        AlbumModel $albumModel,
        AlbumInformationModel $albumInformationModel,
        AlbumLocationModel $albumLocationModel,
        AlbumLocationMediaModel $albumLocationMediaModel,
        SharedAlbumModel $sharedAlbumModel,
        LocationPropertyModel $locationPropertyModel,
        AlbumLocationService $albumLocationService,
        UserModel $userModel,
        ValidationService $validationService,
        CommonService $commonService,
        TemplateService $templateService,
        PdfFileModel $pdfFileModel,
        CompanyUsageModel $companyUsageModel
    )
    {
        $this->_albumPropertyRepository = new Repository($albumPropertyModel);
        $this->_albumTypeRepository = new Repository($albumTypeModel);
        $this->_locationTypeRepository = new Repository($locationTypeModel);
        $this->_albumRepository = new Repository($albumModel);
        $this->_albumInformationRepository = new Repository($albumInformationModel);
        $this->_albumLocationRepository = new Repository($albumLocationModel);
        $this->_albumLocationMediaRepository = new Repository($albumLocationMediaModel);
        $this->_sharedAlbum = new Repository($sharedAlbumModel);
        $this->_locationPropertyRepository = new Repository($locationPropertyModel);
        $this->_albumLocationService = $albumLocationService;
        $this->_userRepository = new Repository($userModel);
        $this->_validationService = $validationService;
        $this->_commonService = $commonService;
        $this->templateService = $templateService;
        $this->pdfFileRepository = new Repository($pdfFileModel);
        $this->companyUsageRepository = new Repository($companyUsageModel);
    }

    public function createAlbum(Array $albumData, int $userId, int $companyId)
    {
        try {
            $this->beginTransaction();
            $albumTypeEntity = $this->_albumTypeRepository->find($albumData['album_type_id']);
            if ($albumTypeEntity->company_id != $companyId) {
                throw new UnprocessableException('messages.not_have_permission');
            }
            $dataCreate = [
                'user_id'       =>  $userId,
                'album_type_id' =>  $albumData['album_type_id'],
                'image_path'    =>  $albumData['image_path'] ?? ""
            ];
            if (array_key_exists('show_comment', $albumData)){
                $dataCreate['show_comment'] = $albumData['show_comment'];
            }
            $albumEntity = $this->_albumRepository->create($dataCreate);

            $this->_insertAlbumInformation($albumEntity, $albumData['information'], $companyId);

            $this->_albumLocationService->insertAlbumLocations($albumEntity, $albumData['locations'], $companyId);

            $this->commitTransaction();
            Util::setPdfFilesByUser($userId);
            return $this->getAlbumDetail($albumEntity->id);
        } catch (NotFoundException $exception) {
            $this->rollbackTransaction();
            throw $exception;
        } catch (UnprocessableException $exception) {
            $this->rollbackTransaction();
            throw $exception;
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            report($e);
            throw new SystemException('messages.system_error');
        }
    }

    protected function _insertAlbumInformation(AlbumModel $albumModel, array $albumInformationData, int $companyId)
    {
        $albumPropertyEntitiesAreFiles = $this->_albumPropertyRepository
            ->where('company_id', "=", $companyId)
            ->where('type', '=', InputType::PDFS)
            ->all()->pluck('id')->toArray();
        $this->_validateInsertAlbumInformation($albumInformationData, $companyId);
        $fileIds = [];
        try {
            $this->beginTransaction();
            $albumModel->albumInformations()->createMany(array_map(function (array $information) use ($albumPropertyEntitiesAreFiles, &$fileIds) {
                if (in_array($information['album_property_id'], $albumPropertyEntitiesAreFiles)) {
                    $fileIds = array_merge($fileIds, array_map(function (array $info) {
                        return $info['id'];
                    }, $information['value_list']));
                }
                return array_merge($information, [
                    'value_list' => array_key_exists('value_list', $information) ? array_map(function (array $info) {
                        return $info['id'];
                    }, $information['value_list']) : [],
                ]);
            }, $albumInformationData));
            $this->updateCompanyUsage($fileIds);
            $this->updateAlbumSize($albumModel, $fileIds);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    private function updateAlbumSize(AlbumModel $albumModel, array $fields, int $type = 1): void
    {
        $size = $this->pdfFileRepository->whereIn('id', $fields)->all()->sum('size');
        $createdUser = $albumModel->user;
        if ($createdUser && $usage = $createdUser->userUsage) {
            $usage->update([
                'count_data' => $type === 1 ? $usage->count_data + $size : $albumModel->size - $size,
            ]);
        }


        $albumModel->update([
            'size' => $type === 1 ? $albumModel->size + $size : $albumModel->size - $size,
        ]);
    }

    private function updateCompanyUsage(array $fileIds, int $type = 1): void
    {
        $size = $this->pdfFileRepository->whereIn('id', $fileIds)->all()->sum('size');
        $usage = app('currentUser')->company->companyUsage;
        if ($type === 2) {
            // minus
            $usage->update([
                'count_data' => $usage->count_data - $size,
            ]);

            return;
        }

        $usage->update([
            'count_data' => $usage->count_data + $size,
        ]);

    }

    private function _validateInsertAlbumInformation(Array $albumInformationData, int $companyId)
    {
        $currentUser = app('currentUser');
        $albumPropertyDataIds = Arr::pluck($albumInformationData, 'album_property_id');
        $albumPropertyEntities = $this->_albumPropertyRepository
            ->where('company_id', "=", $companyId)
            ->all();

        $albumPropertyRequireIds = [];
        $albumPropertyIds = [];
        foreach ($albumPropertyEntities as $albumPropertyEntity) {
            $albumPropertyIds[] = $albumPropertyEntity->id;
            if ($albumPropertyEntity->require) {
                $albumPropertyRequireIds[] = $albumPropertyEntity->id;
            }
        }

        if (count($albumPropertyDataIds) != count(array_unique($albumPropertyDataIds))) {
            throw new UnprocessableException('messages.album_information_is_incorrect');
        }

        foreach ($albumPropertyDataIds as $albumPropertyDataId)
        {
            if (!in_array($albumPropertyDataId, $albumPropertyIds)) {
                throw new NotFoundException('messages.album_property_id_is_incorrect');
            }
        }

        $arrayAlbumInformationData = [];
        foreach ($albumInformationData as $albumInformation)
        {
            if (!empty($albumInformation["value"])) {
                $albumPropertyEntity = $albumPropertyEntities->firstWhere('id', $albumInformation["album_property_id"]);
                $this->_validationService->checkValidateInputType($albumPropertyEntity->title, $albumInformation["value"], $albumPropertyEntity->type, $currentUser->userSetting->language ?? config('app.locale'));
            }
            $arrayAlbumInformationData[$albumInformation["album_property_id"]] = $albumInformation;
        }

        foreach ($albumPropertyRequireIds as $albumPropertyRequireId)
        {
            if (!in_array($albumPropertyRequireId, $albumPropertyDataIds) ||
                empty($arrayAlbumInformationData[$albumPropertyRequireId]['value'])) {
                throw new UnprocessableException('messages.not_enough_information_required_for_album');
            }
        }
    }

    public function getAlbumDetail(int $albumId)
    {
        try {
            $entity = $this->_albumRepository
                ->with([
                    'user',
                    'user.company.albumProperties',
                    'user.company.albumTypes',
                    'albumType',
                    'albumLocations.comments',
                    'albumLocations.albumLocationMedias.comments',
                    'albumInformations.albumProperty'
                ])
                ->find($albumId);
            if (!$entity) {
                return null;
            }
            Util::setPdfFilesByUser($entity->user_id);
            return $entity;
        } catch (\Exception $e) {
            report($e);
            throw new SystemException('messages.system_error');
        }
    }

    protected function _updateOrInsertAlbumInformation(AlbumModel $albumModel, Array $albumInformationData, int $companyId)
    {
        $this->_validateUpdateOrInsertAlbumInformation($albumInformationData, $companyId);

        try {
            $this->beginTransaction();
            $albumPropertyEntitiesWithFiles = $this->_albumPropertyRepository
                ->where('company_id', "=", $companyId)
                ->where('type', '=', InputType::PDFS)
                ->all()->pluck('id')->toArray();

            foreach ($albumInformationData as $albumInformation) {
                if (in_array($albumInformation['album_property_id'], $albumPropertyEntitiesWithFiles)) {
                    $currentValue = $albumModel->albumInformations()->where('album_property_id', '=', $albumInformation['album_property_id'])->first();
                    $this->updateCompanyUsage($currentValue ? $currentValue->value_list : [], 2);
                    $this->updateAlbumSize($albumModel, $currentValue ? $currentValue->value_list : [], 2);
                    $this->updateCompanyUsage(array_key_exists('value_list', $albumInformation) ? array_map(function (array $info) {
                        return $info['id'];
                    }, $albumInformation['value_list']) : []);
                    $this->updateAlbumSize($albumModel, array_key_exists('value_list', $albumInformation) ? array_map(function (array $info) {
                        return $info['id'];
                    }, $albumInformation['value_list']) : []);
                }
                $albumModel->albumInformations()->updateOrCreate(
                    ['album_property_id' => $albumInformation['album_property_id']],
                    array_merge($albumInformation, [
                        'value_list' => array_key_exists('value_list', $albumInformation) ? array_map(function (array $info) {
                            return $info['id'];
                        }, $albumInformation['value_list']) : [],
                    ])
                );
            }

            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    protected function _validateUpdateOrInsertAlbumInformation(Array $albumInformationData, int $companyId)
    {
        $currentUser = app('currentUser');
        $albumPropertyDataIds = Arr::pluck($albumInformationData, 'album_property_id');
        $albumPropertyEntities = $this->_albumPropertyRepository
            ->where('company_id', "=", $companyId)
            ->all();

        $albumPropertyEntitiesIds = $albumPropertyEntities->pluck('id')->toArray();

        if (count($albumPropertyDataIds) != count(array_unique($albumPropertyDataIds))) {
            throw new UnprocessableException('messages.album_information_is_incorrect');
        }

        foreach ($albumPropertyDataIds as $albumPropertyDataId)
        {
            if (!in_array($albumPropertyDataId, $albumPropertyEntitiesIds)) {
                throw new NotFoundException('messages.album_property_id_is_incorrect');
            }
        }
        $arrayAlbumPropertyEntities = [];
        foreach ($albumPropertyEntities as $albumPropertyEntity)
        {
            $arrayAlbumPropertyEntities[$albumPropertyEntity->id] = $albumPropertyEntity;
        }

        foreach ($albumInformationData as $albumInformation)
        {
            if (!empty($albumInformation['value'])) {
                $albumPropertyEntity = $albumPropertyEntities->firstWhere('id', $albumInformation["album_property_id"]);
                $this->_validationService->checkValidateInputType($albumPropertyEntity->title, $albumInformation["value"], $albumPropertyEntity->type, $currentUser->userSetting->language ?? config('app.locale'));
            }
            if($arrayAlbumPropertyEntities[$albumInformation['album_property_id']]->require == AlbumProperty::REQUIRE &&
                empty($albumInformation['value'])) {
                throw new UnprocessableException('messages.not_enough_information_required_for_album');
            }
        }
    }

    public function getAlbumList(int $userId, int $limit, Array $paramQuery): LengthAwarePaginator
    {
        try {
            $userEntity = $this->_userRepository->find($userId);
            if ($userEntity == null) {
                throw new NotFoundException('messages.user_does_not_exist');
            }
            $userIds[] = $userEntity->id;
            if ($this->_commonService->isViewPublicAlbums(json_decode($userEntity->userRole->permissions ?? '[]', true))) {
                $userIds = $userEntity->company->users->pluck('id')->all();
            } else if ($this->_commonService->isViewProtectedAlbums(json_decode($userEntity->userRole->permissions ?? '[]', true))) {
                $subUserIds = $userEntity->subUsers->pluck('id')->all();
                if (!empty($subUserIds)) {
                    $userIds = array_merge($userIds, $subUserIds);
                }
            }
            return $this->_albumRepository
                ->pushCriteria(new SearchAlbumsCriteria($paramQuery))
                ->whereIn('user_id', $userIds)
                ->with(['albumType', 'albumInformations', 'albumInformations.albumProperty', 'user'])
                ->paginate($limit);
        } catch (\Exception $e) {
            report($e);
            throw new SystemException('messages.system_error');
        }
    }

    protected function _updateAlbumInformation(Array $albumData, int $albumId, int $userId, int $companyId)
    {
        $albumEntity = $this->_albumRepository->find($albumId);
        if($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }

        if (!empty($albumData['information'])) {
            $this->_updateOrInsertAlbumInformation($albumEntity, $albumData['information'], $companyId);
        }

        if (!empty($albumData['album_type_id'])) {
            $albumTypeEntity = $this->_albumTypeRepository->find($albumData['album_type_id']);
            if ($albumTypeEntity->company_id != $companyId) {
                throw new UnprocessableException('messages.not_have_permission');
            }
            $albumEntity->album_type_id = $albumData['album_type_id'];
        }

        if (!empty($albumData['image_path'])) {
            $albumEntity->image_path = $albumData['image_path'];
        }

        if (array_key_exists('show_comment', $albumData)) {
            $albumEntity->show_comment = $albumData['show_comment'];
        }

        $albumEntity->save();

        return $albumEntity;
    }

    public function shareAlbumForEmail(Array $shareTarget, int $albumId)
    {
        $userId = app('currentUser')->id;
        $albumEntity = $this->_albumRepository->with(['user.company'])->find($albumId);
        $templateEntity = $shareTarget['template_id'] ? $this->templateService->getTemplateEmailDetail($shareTarget['template_id']) : $this->templateService->getDefaultTemplateEmail();

        if (!$albumEntity) {
            throw new NotFoundException('messages.album_does_not_exist');
        }

        if (!$templateEntity) {
            throw new NotFoundException('messages.template_does_not_exist');
        }

        try {
            $this->beginTransaction();
            $token = base64_encode(Hash::make($userId . now()->timestamp));
            $password = Str::random(10);
            $shareAlbum = $albumEntity->sharedAlbums()->create([
                'user_id'   =>  $userId,
                'full_name' =>  $shareTarget['full_name'],
                'email'     =>  $shareTarget['email'],
                'message'   =>  $shareTarget['message'] ?? null,
                'status'    =>  Boolean::TRUE,
                'token'     =>  $token,
                'password'  =>  Hash::make($password)
            ]);
            $this->_sharedAlbum->update([
                ['user_id', '=', $userId],
                ['id', '<>', $shareAlbum->id],
                ['email', '=', $shareAlbum->email]
            ], [
                'full_name' =>  $shareAlbum->full_name
            ]);
            $link = env('WEB_URL') . WebCommunication::SHARE_ALBUM_PATH. "?token=" . $token;
            $data = array_merge($this->generateDataSharedAlbum($shareAlbum, $link, $password), $this->generateDataAlbum($albumEntity), $this->generateDataCompany($albumEntity->user->company));
            Mail::to($shareAlbum->email)->cc($templateEntity->cc)->bcc($templateEntity->bcc)->queue(new ShareAlbum($templateEntity->subject, $templateEntity->id, $data));
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw $exception;
        }
    }

    public function getListTargetSharedAlbums()
    {
        $userId = app('currentUser')->id;
        $sharedAlbumEntity = $this->_sharedAlbum->where('user_id', '=', $userId)->all(['full_name', 'email']);
        return $sharedAlbumEntity->unique('email')->values()->all();
    }

    public function getListAlbumsByBetweenDays(array $userIds, array $paramQuery)
    {
        return $this->_albumRepository->pushCriteria(new FilterBetweenDayCriteria($paramQuery))->whereIn('user_id', $userIds)->all();
    }

    public function getListSharedAlbumsByBetweenDays(array $userIds, array $paramQuery)
    {
        return $this->_sharedAlbum->pushCriteria(new FilterBetweenDayCriteria($paramQuery))->whereIn('user_id', $userIds)->all();
    }
}
