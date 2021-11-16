<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequests\CheckModifyRequest;
use App\Http\Requests\APIRequests\UpdateAlbumRequest;
use App\Services\APIServices\AlbumsService;
use App\Supports\Facades\Response\Response;
use App\Http\Resources\AlbumDetailResource;
use App\Http\Resources\AlbumLocationDetailResource;

class AlbumsController extends Controller
{
    private $_albumsService;

    public function __construct(AlbumsService $albumsService)
    {
        $this->_albumsService = $albumsService;
    }

    public function updateAlbum(UpdateAlbumRequest $request, int $albumId)
    {
        $currentUser = app('currentUser');
        $albumData = $request->all();
        $result = $this->_albumsService->updateAlbum($albumData, $albumId, $currentUser->id, $currentUser->company_id);
        return Response::success([
            'album' => new AlbumDetailResource($result)
        ]);
    }

    public function getAlbumLocationDetail(int $albumId, int $locationId)
    {
        $userId = app('currentUser')->id;
        $result = $this->_albumsService->getAlbumLocationDetail($userId, $albumId, $locationId);
        return Response::success([
            'location' => new AlbumLocationDetailResource($result)
        ]);
    }

    public function removeAlbum(int $albumId)
    {
        $userId = app('currentUser')->id;
        $this->_albumsService->removeAlbum($albumId, $userId);
        return Response::success();
    }

    public function checkModifyAlbum(CheckModifyRequest $request, int $albumId)
    {
        $updatedAt = $request->updated_at;
        $result = $this->_albumsService->checkModifyAlbum($albumId, $updatedAt);
        return Response::success($result);
    }

    public function getAlbumLocationTitle(int $albumId)
    {
        $result = $this->_albumsService->getAlbumLocationTitle($albumId);
        return Response::success([
            'locations' => $result
        ]);
    }
}
