<?php

namespace App\Http\Controllers\AppControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequests\ChangeLocationOfMediaRequest;
use App\Http\Resources\AlbumDetailResource;
use App\Http\Resources\AlbumLocationMediaResource;
use App\Services\AppService\AlbumLocationMediaService;
use App\Supports\Facades\Response\Response;

class AlbumLocationMediaController extends Controller
{
    private $_albumLocationMediaService;

    public function __construct(AlbumLocationMediaService $albumLocationMediaService)
    {
        $this->_albumLocationMediaService = $albumLocationMediaService;
    }

    public function removeAlbumLocationMedia(int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $currentUser = app('currentUser');
        $this->_albumLocationMediaService->removeAlbumLocationMedia($albumId, $albumLocationId, $albumLocationMediaId);
        return Response::success();
    }

    public function getAlbumLocationMediaDetail(int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $result = $this->_albumLocationMediaService->getAlbumLocationMediaDetail($albumId, $albumLocationId, $albumLocationMediaId);
        return Response::success([
            "media" => new AlbumLocationMediaResource($result)
            ]
        );
    }

    public function changeLocationOfMedia(ChangeLocationOfMediaRequest $request, int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $locationTitle = $request->location_title;
        $result = $this->_albumLocationMediaService->changeLocationOfMedia($albumId, $albumLocationId, $albumLocationMediaId, $locationTitle);
        return Response::success([
            'album' => new AlbumDetailResource($result)
        ]);
    }
}
