<?php

namespace App\Services\WebService;

use App\Constants\Boolean;
use App\Models\AlbumTypeModel;
use App\Constants\AlbumInformation;
use App\Exceptions\SystemException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ForbiddenException;

class AlbumSettingService extends \App\Services\AlbumSettingService
{
    public function addAlbumProperty(Array $albumPropertyData,int $albumTypeId)
    {
        try {
            $arrayAlbumTypeId = $this->_albumTypeRepository->find($albumTypeId,['id','company_id']);
            $companyIdUserUsed = app("currentUser")->company_id;
            if($arrayAlbumTypeId->company_id !== $companyIdUserUsed) {
                throw new ForbiddenException('messages.company_id_is_incorrect');
            }
            $albumPropertyData['album_type_id'] = $albumTypeId;
            $albumPropertyData['company_id'] = $companyIdUserUsed;
            $this->beginTransaction();
            $albumPropertyEntity =  $this->_albumPropertyRepository->create($albumPropertyData);
            $this->commitTransaction();
            return $albumPropertyEntity;
        }
        catch (\Exception $e) {
            report($e);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function addAlbumType(array $albumTypeData)
    {
        $companyId = app('currentUser')->company_id;
        $albumTypeData['company_id'] = $companyId;

        try {
            $albumType = $this->_albumTypeRepository->create([
                'title' => $albumTypeData['title'],
                'description' => $albumTypeData['description'],
                'company_id' => $companyId,
                'default' => Boolean::FALSE,
            ]);

            foreach($albumTypeData['album_information'] as $albumInformation){
                $albumInformation['company_id'] = $companyId;
                $albumInformation['album_type_id'] = $albumType->id;
                $albumPropertyEntity = $this->_albumPropertyRepository->create($albumInformation);
            }

            foreach($albumTypeData['location_information'] as $locationInformation){
                $locationInformation['company_id'] = $companyId;
                $locationInformation['album_type_id'] = $albumType->id;
                $locationPropertyEntity = $this->_locationPropertyRepository->create($locationInformation);
            }

            foreach($albumTypeData['media_information'] as $mediaInformation){
                $mediaInformation['company_id'] = $companyId;
                $mediaInformation['album_type_id'] = $albumType->id;
                $mediaPropertyEntity = $this->_mediaPropertyRepository->create($mediaInformation);
            }
            return $albumType;
        } catch (\Exception $e) {
            report($e);
            throw new SystemException('messages.system_error');
        }
    }

    public function addLocationType(Array $locationTypeData, int $companyId)
    {
        try {
            $locationType = $this->_locationTypeRepository->create([
                'title'         => $locationTypeData['title'],
                'company_id'    => $companyId
            ]);
            return $locationType;
        } catch (\Exception $e) {
            report($e);
            throw new SystemException('messages.system_error');
        }
    }

    public function updateAlbumProperty(array $albumPropertyData, int $albumPropertyId)
    {
        $albumPropertyEntity =  $this->_albumPropertyRepository->find($albumPropertyId);
        if ($albumPropertyEntity == null) {
            throw new NotFoundException('messages.album_property_does_not_exist');
        }

        try {
            $this->beginTransaction();
            $albumPropertyEntity->update($albumPropertyData);
            $this->commitTransaction();
            return $albumPropertyEntity;
        }
        catch (\Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function updateAlbumType(array $albumTypeData, int $albumTypeId)
    {
        $albumTypeEntity =  $this->_albumTypeRepository->find($albumTypeId);
        if (!$albumTypeEntity) {
            throw new NotFoundException('messages.album_type_does_not_exist');
        }

        if ($albumTypeEntity['default'] === Boolean::TRUE && $albumTypeData['default'] === Boolean::FALSE) {
            abort(403,"messages.album_type_does_not_update");
        }
        
        try {
            $albumTypeEntity->update($albumTypeData);

            if ($albumTypeData['default'] === Boolean::TRUE) {
                $this->_albumTypeRepository
                ->where('company_id', '=' , app('currentUser')->company_id)
                ->update([['id', '!=' , $albumTypeId]],['default' => Boolean::FALSE]);
            }
            return $albumTypeEntity;
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function updateLocationType(Array $locationTypeData, int $companyId, int $locationTypeId)
    {
        $locationTypeEntity =  $this->_locationTypeRepository->find($locationTypeId);
        if ($locationTypeEntity == null) {
            throw new NotFoundException('messages.location_type_does_not_exist');
        }
        if ($locationTypeEntity->company_id != $companyId) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        try {
            $locationTypeEntity->update($locationTypeData);
            return $locationTypeEntity;
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function deleteAlbumProperty(int $albumPropertyId)
    {
        $albumPropertyEntity =  $this->_albumPropertyRepository->find($albumPropertyId);
        if ($albumPropertyEntity == null) {
            throw new NotFoundException('messages.album_property_does_not_exist');
        }
        try {
            $albumPropertyEntity->delete();
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function deleteAlbumType(int $albumTypeId)
    {
        $albumTypeEntity =  $this->_albumTypeRepository->find($albumTypeId);
        if (!$albumTypeEntity) {
            throw new NotFoundException('messages.album_type_does_not_exist');
        }

        if ($albumTypeEntity->albums()->count() > 0) {
            throw new ForbiddenException('messages.cannot_delete_album_type');
        }

        if ($albumTypeEntity["default"] === Boolean::TRUE) {
            throw new ForbiddenException('messages.cannot_delete_album_type');
        }

        try {
            $albumTypeEntity->delete();
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function deleteLocationType(int $companyId, int $locationTypeId)
    {
        $locationTypeEntity =  $this->_locationTypeRepository->find($locationTypeId);
        if ($locationTypeEntity == null) {
            throw new NotFoundException('messages.location_type_does_not_exist');
        }
        if ($locationTypeEntity->company_id != $companyId) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        try {
            $locationTypeEntity->delete();
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function addLocationProperty(array $locationPropertyData, int $companyId)
    {
        try {
            $this->beginTransaction();
            $locationPropertyData['company_id'] = $companyId;
            $locationPropertyEntity =  $this->_locationPropertyRepository->create($locationPropertyData);
            $this->commitTransaction();
            return $locationPropertyEntity;
        } catch (\Exception $e) {
            report($e);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function updateLocationProperty(Array $locationPropertyData, int $locationPropertyId)
    {
        $locationPropertyEntity =  $this->_locationPropertyRepository->find($locationPropertyId);
        if ($locationPropertyEntity == null) {
            throw new NotFoundException('messages.location_property_does_not_exist');
        }
        try {
            $this->beginTransaction();
            $locationPropertyEntity->update($locationPropertyData);
            $this->commitTransaction();
            return $locationPropertyEntity;
        } catch (\Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function deleteLocationProperty(int $locationPropertyId)
    {
        $locationPropertyEntity =  $this->_locationPropertyRepository->find($locationPropertyId);
        if ($locationPropertyEntity == null) {
            throw new NotFoundException('messages.location_property_does_not_exist');
        }
        try {
            $locationPropertyEntity->delete();
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function addMediaProperty(array $mediaPropertyData, int $albumTypeId)
    {
        try {
            $this->beginTransaction();
            $mediaPropertyData['album_type_id'] = $albumTypeId;
            $mediaPropertyEntity =  $this->_mediaPropertyRepository->create($mediaPropertyData);
            $this->commitTransaction();
            return $mediaPropertyEntity;
        } catch (\Exception $e) {
            report($e);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function updateMediaProperty(array $mediaPropertyData, int $albumTypeId, int $mediaPropertyId)
    {
        $mediaPropertyEntity =  $this->_mediaPropertyRepository->find($mediaPropertyId);
        if (!$mediaPropertyEntity) {
            throw new NotFoundException('messages.media_property_does_not_exist');
        }
        if ($mediaPropertyEntity->album_type_id !== $albumTypeId) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        try {
            $this->beginTransaction();
            $mediaPropertyEntity->update($mediaPropertyData);
            $this->commitTransaction();
            return $mediaPropertyEntity;
        } catch (\Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function deleteMediaProperty(int $albumTypeId, int $mediaPropertyId)
    {
        $mediaPropertyEntity =  $this->_mediaPropertyRepository->find($mediaPropertyId);
        if (!$mediaPropertyEntity) {
            throw new NotFoundException('messages.media_property_does_not_exist');
        }
        if ($mediaPropertyEntity->album_type_id !== $albumTypeId) {
            throw new ForbiddenException('messages.not_have_permission');
        }
        try {
            $mediaPropertyEntity->delete();
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function checkAlbumTypeByCurrentUser(int $albumTypeId)
    {
        $companyId = $currentUser = app('currentUser')->company_id;
        $albumType = $this->_albumTypeRepository->where('company_id','=',$companyId)->find($albumTypeId);
        if(!$albumType)
        {
            throw new NotFoundException('message.not_fond_album_type');
        }
        return true;
    }
    public function checkLocationPropertyByCurrentUser(int $locationPropertyId , int $albumTypeId)
    {
        $locationProperty = $this->_locationPropertyRepository->where('album_type_id','=',$albumTypeId)->find($locationPropertyId);
        if(!$locationProperty){
            throw new NotFoundException('messages.not_fond_location_property');
        }
        return true;
    }
    public function checkAlbumPropertyByCurrentUser(int $albumPropertyId, int $albumTypeId)
    {
        $albumProperty = $this->_albumPropertyRepository->where('album_type_id','=',$albumTypeId)->find($albumPropertyId);
        if(!$albumProperty){
            throw new NotFoundException('messages.not_fond_album_property');
        }
        return true;
    }
    public function checkAlbumConfigByCurrentUser(int $companyId , int $albumTypeId)
    {
        $albumType = $this->_albumTypeRepository->where('company_id','=',$companyId)->find($albumTypeId);
        if(!$albumType){
            throw new NotFoundException('messages.not_fond_album_property');
        }
        return $albumType;
    }

    public function getCurrentCompanyAlbumTypeById(int $albumTypeId)
    {
        $companyId = app('currentUser')->company_id;
        $albumTypeEntity = $this->_albumTypeRepository
            ->where('company_id','=',$companyId)->find($albumTypeId);
        if(!$albumTypeEntity){
            abort(404,"messages.company_id_not_found");
        }
        return $albumTypeEntity;
    }
}

