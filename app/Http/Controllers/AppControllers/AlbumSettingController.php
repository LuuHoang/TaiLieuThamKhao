<?php

namespace App\Http\Controllers\AppControllers;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumPropertyResource;
use App\Http\Resources\AlbumTypeResource;
use App\Http\Resources\LocationPropertyResource;
use App\Http\Resources\LocationTypeResource;
use App\Http\Resources\MediaPropertyResource;
use App\Services\AppService\AlbumSettingService;
use App\Supports\Facades\Response\Response;

class AlbumSettingController extends Controller
{
    private $_albumSettingService;

    public function __construct(AlbumSettingService $albumSettingService)
    {
        $this->_albumSettingService = $albumSettingService;
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
}
