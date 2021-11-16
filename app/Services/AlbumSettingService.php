<?php

namespace App\Services;

use App\Exceptions\SystemException;
use App\Models\AlbumPropertyModel;
use App\Models\AlbumTypeModel;
use App\Models\CompanyModel;
use App\Models\LocationPropertyModel;
use App\Models\LocationTypeModel;
use App\Models\MediaPropertyModel;
use App\Repositories\Repository;

class AlbumSettingService extends AbstractService
{
    protected $_albumPropertyRepository;
    protected $_albumTypeRepository;
    protected $_locationTypeRepository;
    protected $_companyRepository;
    protected $_locationPropertyRepository;
    protected $_mediaPropertyRepository;

    public function __construct(
        AlbumPropertyModel $albumPropertyModel,
        AlbumTypeModel $albumTypeModel,
        LocationTypeModel $locationTypeModel,
        CompanyModel $companyModel,
        LocationPropertyModel $locationPropertyModel,
        MediaPropertyModel $mediaPropertyModel
    )
    {
        $this->_albumPropertyRepository = new Repository($albumPropertyModel);
        $this->_albumTypeRepository = new Repository($albumTypeModel);
        $this->_locationTypeRepository = new Repository($locationTypeModel);
        $this->_companyRepository = new Repository($companyModel);
        $this->_locationPropertyRepository = new Repository($locationPropertyModel);
        $this->_mediaPropertyRepository = new Repository($mediaPropertyModel);
    }

    public function getAlbumSettings(int $companyId)
    {
        $companyEntity = $this->_companyRepository->find($companyId);
        if ($companyEntity == null) {
            throw new SystemException('messages.system_error');
        }

        return [
            "album_properties"      => $companyEntity->albumProperties,
            "album_types"           => $companyEntity->albumTypes,
            "location_properties"   => $companyEntity->locationProperties,
            "location_types"        => $companyEntity->locationTypes,
            "media_properties"      => $companyEntity->mediaProperties,
        ];
    }

    public function getAlbumLocationTypes(int $companyId)
    {
        return $this->_locationTypeRepository->where('company_id', '=', $companyId)->all();
    }

    public function getAlbumProperties(int $companyId)
    {
        return $this->_albumPropertyRepository->where('company_id', '=', $companyId)->all();
    }
    public function getAlbumPropertiesByAlbumType(int $companyId,int $albumTypeId)
    {
        return $this->_albumPropertyRepository->where('company_id', '=', $companyId)
            ->where('album_type_id','=',$albumTypeId)
            ->all();
    }

    public function getLocationProperties(int $companyId)
    {
        return $this->_locationPropertyRepository->where('company_id', '=', $companyId)->all();
    }

    public function getMediaProperties(int $companyId)
    {
        return $this->_mediaPropertyRepository->where('company_id', '=', $companyId)->all();
    }

    public function getAlbumTypes()
    {
        return $this->_albumTypeRepository->where('company_id', '=', app('currentUser')->company_id)->all();

    }

    public function getLocationSettings(int $companyId)
    {
        $companyEntity = $this->_companyRepository->find($companyId);
        if ($companyEntity == null) {
            throw new SystemException('messages.system_error');
        }

        return [
            "location_properties"   => $companyEntity->locationProperties,
            "location_types"        => $companyEntity->locationTypes
        ];
    }
}
