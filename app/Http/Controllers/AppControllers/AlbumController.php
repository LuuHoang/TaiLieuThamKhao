<?php

namespace App\Http\Controllers\AppControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequests\CreateAlbumRequest;
use App\Http\Requests\AppRequests\ShareAlbumForEmailRequest;
use App\Http\Requests\AppRequests\UpdateAlbumRequest;
use App\Http\Requests\UploadAvatarRequest;
use App\Http\Resources\AlbumDetailResource;
use App\Http\Resources\AlbumListResource;
use App\Services\AppService\AlbumService;
use App\Services\UploadService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    private $_albumsService;
    private $_uploadService;

    public function __construct(
        AlbumService $albumService,
        UploadService $uploadService
    )
    {
        $this->_albumsService = $albumService;
        $this->_uploadService = $uploadService;
    }

    public function createAlbum(CreateAlbumRequest $request)
    {
        $currentUser = app('currentUser');
        $albumData = $request->all();
        $result = $this->_albumsService->createAlbum($albumData, $currentUser->id, $currentUser->company_id);
        return Response::success([
            'album' => new AlbumDetailResource($result)
        ]);
    }

    public function updateAlbum(UpdateAlbumRequest $request, int $albumId)
    {
        $currentUser = app('currentUser');
        $albumData = $request->all();
        $forceUpdate = $request->header('force_update', 0);
        $forceUpdate = (bool)$forceUpdate;
        $result = $this->_albumsService->updateAlbum($albumData, $albumId, $currentUser->id, $currentUser->company_id, $forceUpdate);
        return Response::success([
            'album' => new AlbumDetailResource($result)
        ]);
    }

    public function getAlbums(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort']);
        $userId = app('currentUser')->id;

        $result = $this->_albumsService->getAlbumList($userId, $limit, $paramQuery);

        return Response::pagination(
            AlbumListResource::collection($result),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    public function getAlbumDetail(int $albumId)
    {
        $userId = app('currentUser')->id;
        $result = $this->_albumsService->getAlbumDetail($albumId);
        return Response::success([
            'album' => new AlbumDetailResource($result)
        ]);
    }

    public function updateAlbumAvatar(UploadAvatarRequest $request, int $albumId)
    {
        $image = $request->file('file');
        $imageResponse = $this->_uploadService->updateAlbumAvatar($image, $albumId);
        return Response::success([
            'image_data'    =>  $imageResponse
        ]);
    }

    public function shareAlbumForEmail(ShareAlbumForEmailRequest $request, int $albumId)
    {
        $shareTarget = $request->only('email', 'full_name', 'message', 'template_id');
        $this->_albumsService->shareAlbumForEmail($shareTarget, $albumId);
        return Response::success();
    }

    public function getListTargetSharedAlbums()
    {
        $targetSharedAlbum = $this->_albumsService->getListTargetSharedAlbums();
        return Response::success([
            'data_list' => $targetSharedAlbum
        ]);
    }
}
