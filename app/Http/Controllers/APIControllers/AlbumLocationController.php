<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequests\CheckModifyRequest;
use App\Services\APIServices\AlbumLocationService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class AlbumLocationController extends Controller
{
    private $_albumLocationService;

    public function __construct(AlbumLocationService $albumLocationService)
    {
        $this->_albumLocationService = $albumLocationService;
    }

    public function editCommentLocation(Request $request, int $albumId, int $albumLocationId)
    {
        $currentUser = app('currentUser');
        $comment = $request->get('comment');
        $this->_albumLocationService->editCommentAlbumLocation($comment, $albumId, $albumLocationId, $currentUser->id);
        return Response::success();
    }

    public function editCommentLocationMedia(Request $request, int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $currentUser = app('currentUser');
        $comment = $request->get('comment');
        $this->_albumLocationService->editCommentAlbumLocationMedia($comment, $albumId, $albumLocationId, $albumLocationMediaId, $currentUser->id);
        return Response::success();
    }

    public function checkModifyLocation(CheckModifyRequest $request, int $albumId, int $locationId)
    {
        $updatedAt = $request->updated_at;
        $result = $this->_albumLocationService->checkModifyLocation($albumId, $locationId, $updatedAt);
        return Response::success($result);
    }

    public function checkModifyMedia(CheckModifyRequest $request, int $albumId, int $locationId, int $mediaId)
    {
        $updatedAt = $request->updated_at;
        $result = $this->_albumLocationService->checkModifyMedia($albumId, $locationId, $mediaId, $updatedAt);
        return Response::success($result);
    }
}
