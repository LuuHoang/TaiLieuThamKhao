<?php

namespace App\Http\Controllers\AppControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequests\CreateAlbumLocationRequest;
use App\Http\Requests\AppRequests\UpdateAlbumLocationRequest;
use App\Http\Resources\AlbumLocationResource;
use App\Services\AppService\AlbumLocationService;
use App\Supports\Facades\Response\Response;

class AlbumLocationController extends Controller
{
    private $_albumLocationService;

    public function __construct(AlbumLocationService $albumLocationService)
    {
        $this->_albumLocationService = $albumLocationService;
    }

    public function createAlbumLocation(CreateAlbumLocationRequest $request, int $albumId)
    {
        $currentUser = app('currentUser');
        $albumLocationData = $request->only(['locations']);
        $newAlbumLocations = $this->_albumLocationService->createAlbumLocations($albumLocationData, $albumId, $currentUser->company_id);
        return Response::success([
            'locations' =>  AlbumLocationResource::collection($newAlbumLocations)
        ]);
    }

    public function updateAlbumLocation(UpdateAlbumLocationRequest $request, int $albumId, int $locationId)
    {
        $currentUser = app('currentUser');
        $albumLocationData = $request->only(['description', 'information', 'medias', 'latest_updated_at']);
        $forceUpdate = $request->header('force_update', 0);
        $forceUpdate = (bool)$forceUpdate;
        $newAlbumLocation = $this->_albumLocationService->updateAlbumLocation($albumLocationData, $locationId, $albumId, $currentUser->company_id, $forceUpdate);
        return Response::success([
            'location' =>  new AlbumLocationResource($newAlbumLocation)
        ]);
    }

    public function removeAlbumLocation(int $albumId, int $albumLocationId)
    {
        $this->_albumLocationService->removeAlbumLocation($albumId, $albumLocationId);
        return Response::success();
    }
}
