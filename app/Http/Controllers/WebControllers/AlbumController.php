<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadAvatarRequest;
use App\Http\Requests\WebRequests\ConfigNameAlbumRequest;
use App\Http\Requests\WebRequests\CreateAlbumRequest;
use App\Http\Requests\WebRequests\GetSharedAlbumRequest;
use App\Http\Requests\WebRequests\ShareAlbumForEmailRequest;
use App\Http\Requests\WebRequests\UpdateAlbumRequest;
use App\Http\Resources\AlbumDetailForShareUserResource;
use App\Http\Resources\AlbumDetailResource;
use App\Http\Resources\SharedAlbumResource;
use App\Http\Resources\WebResources\AlbumListResource;
use App\Services\ExportService;
use App\Services\UploadService;
use App\Services\WebService\AlbumService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    private $_albumsService;
    private $_uploadService;
    private $_exportService;

    public function __construct(
        AlbumService $albumService,
        UploadService $uploadService,
        ExportService $exportService
    )
    {
        $this->_albumsService = $albumService;
        $this->_uploadService = $uploadService;
        $this->_exportService = $exportService;
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
        $paramQuery = $request->only(['search', 'sort', 'filter']);
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

    public function getSharedAlbum(GetSharedAlbumRequest $request)
    {
        $dataRequest = $request->only('token', 'password');
        $album = $this->_albumsService->getSharedAlbum($dataRequest);
        return Response::success([
            'album' => new AlbumDetailForShareUserResource($album)
        ]);
    }

    public function getListSharedAlbums(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort']);
        $userId = app('currentUser')->id;

        $ListSharedAlbums = $this->_albumsService->getListSharedAlbums($userId, $paramQuery, $limit);

        return Response::pagination(
            SharedAlbumResource::collection($ListSharedAlbums),
            $ListSharedAlbums->total(),
            $ListSharedAlbums->currentPage(),
            $limit
        );
    }

    public function changeStatusSharedAlbum(int $sharedAlbumId)
    {
        $sharedAlbum = $this->_albumsService->changeStatusSharedAlbum($sharedAlbumId);
        return Response::success([
            'shared_data' => new SharedAlbumResource($sharedAlbum)
        ]);
    }

    public function getListTargetSharedAlbums()
    {
        $targetSharedAlbum = $this->_albumsService->getListTargetSharedAlbums();
        return Response::success([
            'data_list' => $targetSharedAlbum
        ]);
    }

    public function getConfigNameAlbum(int $albumId)
    {
        $response = $this->_albumsService->getConfigNameAlbum($albumId);
        return Response::success([
            'data_config' => $response['data_config'],
            'data_properties'   =>  $response['data_properties']
        ]);
    }

    public function setConfigNameAlbum(ConfigNameAlbumRequest $request, int $albumId)
    {
        $config = $request->only('config');
        $configData = $config['config'] ?? null;
        $response = $this->_albumsService->setConfigNameAlbum($albumId, $configData);
        return Response::success([
            'data_config' => $response['data_config'],
            'data_properties'   =>  $response['data_properties']
        ]);
    }

    public function downloadMediaByNameConfig(Request $request, int $albumId)
    {
        $strMediaIds = $request->medias;
        $mediaIds = explode(',', $strMediaIds);
        $response = $this->_albumsService->downloadMediaByNameConfig($albumId, $mediaIds);
        return Response::success($response);
    }

    public function exportAlbumPDF(Request $request, int $albumId)
    {
        $style = $request->style;
        $response = $this->_exportService->exportingAlbumPDF($albumId, $style);
        return Response::success($response);
    }

    public function exportPDFAlbum(Request $request, int $albumId) {
        $style = $request->style;
        $response = $this->_albumsService->exportPDFAlbum($albumId, $style);
        return Response::success([
            'url'  =>  $response
        ]);
    }

    public function exportAlbumPDFFile(Request $request, int $albumId)
    {
        $formatId = $request->format_id;
        $response = $this->_exportService->exportAlbumPDFFile($albumId, $formatId);
        return Response::success($response);
    }

    public function previewAlbumPDF(Request $request, int $albumId)
    {
        $formatId = $request->format_id;
        $response = $this->_exportService->previewAlbumPDF($albumId, $formatId);
        return Response::success($response);
    }
}
