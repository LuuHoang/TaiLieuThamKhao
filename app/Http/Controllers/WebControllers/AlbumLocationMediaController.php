<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\ChangeLocationOfMediaRequest;
use App\Services\WebService\AlbumLocationMediaService;
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
        $this->_albumLocationMediaService->removeAlbumLocationMedia($albumId, $albumLocationId, $albumLocationMediaId);
        return Response::success();
    }

    public function changeLocationOfMedia(ChangeLocationOfMediaRequest $request, int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $locationTitle = $request->location_title;
        $this->_albumLocationMediaService->changeLocationOfMedia($albumId, $albumLocationId, $albumLocationMediaId, $locationTitle);
        return Response::success();
    }
}
