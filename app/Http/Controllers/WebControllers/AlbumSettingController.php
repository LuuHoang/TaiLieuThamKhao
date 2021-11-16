<?php

namespace App\Http\Controllers\WebControllers;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\AddAlbumPropertyRequest;
use App\Http\Requests\WebRequests\addAlbumTypeRequest;
use App\Http\Requests\WebRequests\AddLocationPropertyRequest;
use App\Http\Requests\WebRequests\AddLocationTypeRequest;
use App\Http\Requests\WebRequests\AddMediaPropertyRequest;
use App\Http\Requests\WebRequests\UpdateAlbumPropertyRequest;
use App\Http\Requests\WebRequests\UpdateAlbumTypeRequest;
use App\Http\Requests\WebRequests\UpdateLocationPropertyRequest;
use App\Http\Requests\WebRequests\UpdateLocationTypeRequest;
use App\Http\Requests\WebRequests\UpdateMediaPropertyRequest;
use App\Http\Resources\AlbumPropertyResource;
use App\Http\Resources\AlbumTypeResource;
use App\Http\Resources\LocationPropertyResource;
use App\Http\Resources\LocationTypeResource;
use App\Http\Resources\MediaPropertyResource;
use App\Services\WebService\AlbumSettingService;
use App\Supports\Facades\Response\Response;

class AlbumSettingController extends Controller
{
    private $_albumSettingService;

    public function __construct(AlbumSettingService $albumSettingService)
    {
        $this->_albumSettingService = $albumSettingService;
    }

    public function addAlbumProperty(AddAlbumPropertyRequest $request, int $albumTypeId)
    {
        if (!isset($albumTypeId))
            throw new ForbiddenException('messages.send_album_type_id_pls');

        $albumPropertyParams = $request->only(['title', 'description', 'type', 'require', 'display', 'highlight']);
        $albumProperty = $this->_albumSettingService->addAlbumProperty($albumPropertyParams,$albumTypeId);

        return Response::success([
            'album_property'    =>  new AlbumPropertyResource($albumProperty)
        ]);
    }

    public function addAlbumType(addAlbumTypeRequest $request)
    {
        $albumTypeParams = $request->only(['title', 'description', 'album_information', 'media_information', 'location_information']);

        $albumType = $this->_albumSettingService->addAlbumType($albumTypeParams);

        return Response::success([
            'album_type'    =>  new AlbumTypeResource($albumType)
        ]);
    }

    public function addLocationType(AddLocationTypeRequest $request, int $companyId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id)
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $locationTypeParams = $request->only(['title']);
        $locationType = $this->_albumSettingService->addLocationType($locationTypeParams, $companyId);

        return Response::success([
            'location_type' =>  new LocationTypeResource($locationType)
        ]);
    }

    public function getAlbumSetting(int $companyId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id)
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $albumSetting = $this->_albumSettingService->getAlbumSettings($companyId);

        $albumPropertiesResource    = AlbumPropertyResource::collection($albumSetting["album_properties"]);
        $albumTypesResource         = AlbumTypeResource::collection($albumSetting["album_types"]);
        $locationTypesResource      = LocationTypeResource::collection($albumSetting["location_types"]);
        $locationPropertiesResource = LocationPropertyResource::collection($albumSetting["location_properties"]);
        $mediaPropertiesResource    = MediaPropertyResource::collection($albumSetting["media_properties"]);

        return Response::success([
            "album_settings" => [
                "album_properties"      => $albumPropertiesResource,
                "album_types"           => $albumTypesResource,
                "location_properties"   => $locationPropertiesResource,
                "location_types"        => $locationTypesResource,
                "media_properties"      => $mediaPropertiesResource,
            ]
        ]);
    }

    public function getLocationAlbum(int $companyId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id)
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $albumLocation = $this->_albumSettingService->getAlbumLocationTypes($companyId);
        return Response::success([
            "location_types" => LocationTypeResource::collection($albumLocation)
        ]);
    }

    public function updateAlbumProperty(UpdateAlbumPropertyRequest $request, int $albumTypeId, int $albumPropertyId)
    {
        if (!$this->_albumSettingService->checkAlbumPropertyByCurrentUser($albumPropertyId,$albumTypeId))
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $albumPropertyParams = $request->only(['description', 'require', 'display', 'highlight']);
        $albumProperty = $this->_albumSettingService->updateAlbumProperty($albumPropertyParams, $albumPropertyId);
        return Response::success([
            'album_property'    =>  new AlbumPropertyResource($albumProperty)
        ]);
    }
    public function updateAlbumType(UpdateAlbumTypeRequest $request, int $albumTypeId)
    {
        $this->_albumSettingService->getCurrentCompanyAlbumTypeById($albumTypeId);

        $albumTypeParams = $request->only(['title', 'description', 'default']);
        $albumType = $this->_albumSettingService->updateAlbumType($albumTypeParams, $albumTypeId);
        return Response::success([
            'album_type'    =>  new AlbumTypeResource($albumType)
        ]);
    }
    public function updateLocationType(UpdateLocationTypeRequest $request, int $companyId, int $locationTypeId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id)
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $locationTypeParams = $request->only(['title']);
        $locationType = $this->_albumSettingService->updateLocationType($locationTypeParams, $companyId, $locationTypeId);
        return Response::success([
            'location_type' =>  new LocationTypeResource($locationType)
        ]);
    }

    public function deleteAlbumProperty( int $albumTypeId, int $albumPropertyId )
    {
        if (!$this->_albumSettingService->checkAlbumPropertyByCurrentUser($albumPropertyId,$albumTypeId))
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $this->_albumSettingService->deleteAlbumProperty($albumPropertyId);
        return Response::success();
    }
    public function deleteAlbumType(int $albumTypeId)
    {
        $this->_albumSettingService->getCurrentCompanyAlbumTypeById($albumTypeId);

        $this->_albumSettingService->deleteAlbumType($albumTypeId);
        return Response::success();
    }
    public function deleteLocationType(int $companyId, int $locationTypeId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id)
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $this->_albumSettingService->deleteLocationType($companyId, $locationTypeId);
        return Response::success();
    }

    public function getAlbumProperties(int $companyId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id)
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $albumProperties = $this->_albumSettingService->getAlbumProperties($companyId);
        return Response::success([
            "album_properties" => AlbumPropertyResource::collection($albumProperties)
        ]);
    }
    public function getAlbumConfigByAlbumType(int $albumTypeId)
    {
        $companyId = app('currentUser')->company_id;
        $albumType = $this->_albumSettingService->checkAlbumConfigByCurrentUser($companyId, $albumTypeId);
        return Response::success([
            "album_information_config" => new AlbumTypeResource($albumType)
        ]);
    }

    public function addLocationProperty(AddLocationPropertyRequest $request ,int $albumTypeId)
    {
        $companyId = $currentUser = app('currentUser')->company_id;
        if (!$this->_albumSettingService->checkAlbumTypeByCurrentUser($albumTypeId))
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $locationPropertyParams = $request->only(['title', 'type', 'display', 'highlight','description','require']);
        $locationPropertyParams['album_type_id'] = $albumTypeId;
        $locationProperty = $this->_albumSettingService->addLocationProperty($locationPropertyParams, $companyId);

        return Response::success([
            'location_property'    =>  new LocationPropertyResource($locationProperty)
        ]);
    }

    public function updateLocationProperty(UpdateLocationPropertyRequest $request,int $albumTypeId, int $locationPropertyId)
    {
        if (!$this->_albumSettingService->checkLocationPropertyByCurrentUser($locationPropertyId, $albumTypeId))
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $locationPropertyParams = $request->only(['display', 'highlight','description','require']);
        $locationProperty = $this->_albumSettingService->updateLocationProperty($locationPropertyParams, $locationPropertyId);
        return Response::success([
            'location_property'    =>  new LocationPropertyResource($locationProperty)
        ]);
    }

    public function deleteLocationProperty(int $albumTypeId,int $locationPropertyId)
    {
        if (!$this->_albumSettingService->checkLocationPropertyByCurrentUser($locationPropertyId, $albumTypeId))
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $this->_albumSettingService->deleteLocationProperty($locationPropertyId);
        return Response::success();
    }

    public function addMediaProperty(AddMediaPropertyRequest $request, int $albumTypeId)
    {
        $mediaPropertyParams = $request->only(['title', 'type', 'display', 'highlight', 'description', 'require']);

        $this->_albumSettingService->getCurrentCompanyAlbumTypeById($albumTypeId);
        $mediaPropertyParams['company_id'] = app('currentUser')->company_id;

        $mediaProperty = $this->_albumSettingService->addMediaProperty($mediaPropertyParams, $albumTypeId);
        return Response::success([
            'media_property'    =>  new MediaPropertyResource($mediaProperty)
        ]);
    }

    public function updateMediaProperty(UpdateMediaPropertyRequest $request, int $albumTypeId, int $mediaPropertyId)
    {
        $mediaPropertyParams = $request->only(['display', 'highlight', 'description', 'require']);

        $this->_albumSettingService->getCurrentCompanyAlbumTypeById($albumTypeId);
        $mediaPropertyParams['company_id'] = app('currentUser')->company_id;

        $mediaProperty = $this->_albumSettingService->updateMediaProperty($mediaPropertyParams, $albumTypeId, $mediaPropertyId);
        return Response::success([
            'media_property'    =>  new MediaPropertyResource($mediaProperty)
        ]);
    }

    public function deleteMediaProperty(int $albumTypeId, int $mediaPropertyId)
    {
        $this->_albumSettingService->getCurrentCompanyAlbumTypeById($albumTypeId);

        $this->_albumSettingService->deleteMediaProperty($albumTypeId, $mediaPropertyId);
        return Response::success();
    }

    public function getLocationProperties(int $companyId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id)
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $locationProperties = $this->_albumSettingService->getLocationProperties($companyId);
        return Response::success([
            "location_properties" => LocationPropertyResource::collection($locationProperties)
        ]);
    }

    public function getMediaProperties(int $companyId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id)
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $mediaProperties = $this->_albumSettingService->getMediaProperties($companyId);
        return Response::success([
            "media_properties" => MediaPropertyResource::collection($mediaProperties)
        ]);
    }

    public function getTypeAlbum()
    {
        $albumTypes = $this->_albumSettingService->getAlbumTypes();
        return Response::success([
            "album_types" => AlbumTypeResource::collection($albumTypes)
        ]);
    }

    public function getLocationSetting(int $companyId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id)
            throw new ForbiddenException('messages.company_id_is_incorrect');

        $albumSetting = $this->_albumSettingService->getLocationSettings($companyId);

        $locationPropertiesResource    = LocationPropertyResource::collection($albumSetting["location_properties"]);
        $locationTypesResource      = LocationTypeResource::collection($albumSetting["location_types"]);

        return Response::success([
            "location_settings" => [
                "location_properties" => $locationPropertiesResource,
                "location_types" => $locationTypesResource,
            ]
        ]);
    }
}
