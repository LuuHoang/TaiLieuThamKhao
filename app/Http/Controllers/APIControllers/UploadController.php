<?php

namespace App\Http\Controllers\APIControllers;

use App\Constants\Disk;
use App\Constants\Media;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequests\UpdateAlbumLocationMediaRequest;
use App\Http\Requests\APIRequests\UpdateImageAlbumLocationMediaRequest;
use App\Http\Requests\APIRequests\UploadAlbumPdfRequest;
use App\Http\Requests\UploadAvatarRequest;
use App\Http\Requests\UploadMediasRequest;
use App\Http\Resources\AlbumLocationMediaResource;
use App\Http\Resources\PdfFileResource;
use App\Services\APIServices\UploadService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    private $_uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->_uploadService = $uploadService;
    }


    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $userId = app('currentUser')->id;
        $image = $this->_uploadService->uploadAvatar($request, $userId);

        if (!$image) {
            throw new SystemException('messages.system_error');
        }

        return Response::success([
            'user_id' => $userId,
            'image_path' => $image,
            'image_url' => Storage::disk(Disk::USER)->url($image)
        ]);
    }

    public function uploadAlbumAvatar(UploadAvatarRequest $request)
    {
        $userId = app('currentUser')->id;
        $image = $this->_uploadService->uploadAlbumAvatar($request, $userId);

        if (!$image) {
            throw new SystemException('messages.system_error');
        }

        return Response::success([
            'user_id' => $userId,
            'image_path' => $image,
            'image_url' => Storage::disk(Disk::IMAGE)->url($image)
        ]);
    }

    public function prepareFileUpload(Request $request)
    {
        $userId = app('currentUser')->id;
        if (!$this->_uploadService->allowedToUpload($userId, $request->size)) {
            throw new UnprocessableException('messages.file_size_exceeds_the_maximum_allowed_size');
        }

        return Response::success();
    }

    public function uploadAlbumMedias(UploadMediasRequest $request)
    {
        $userId = app('currentUser')->id;
        $files = $request->file('files');
        $totalSize = $this->getSize($files);

        if (!$this->_uploadService->allowedToUpload($userId, $totalSize)) {
            throw new UnprocessableException('messages.file_size_exceeds_the_maximum_allowed_size');
        }

        $mediaType = (int)$request->get('media_type');
        if ($mediaType === Media::TYPE_IMAGE) {
            $imagesData = $this->_uploadService->uploadAlbumImages($files, $mediaType, $userId);
            return Response::success([
                'files' => $imagesData
            ]);
        } else if ($mediaType === Media::TYPE_VIDEO) {
            $videosData = $this->_uploadService->uploadAlbumVideos($files, $mediaType, $userId);
            return Response::success([
                'files' => $videosData
            ]);
        } else {
            throw new UnprocessableException('messages.media_type_format_is_incorrect');
        }
    }

    public function getSize($files)
    {
        $totalSize = 0;
        foreach ($files as $file) {
            $totalSize += $file->getSize();
        }

        return $totalSize;
    }

    public function insertAlbumLocationMedias(UploadMediasRequest $request, int $albumId, int $albumLocationId)
    {
        $user = app('currentUser');
        $dataUpload = $request->only(['medias', 'media_type']);
        $albumLocationMedias = $this->_uploadService->insertAlbumLocationMedias($user->company_id, $user->id, $albumId, $albumLocationId, $dataUpload);
        return Response::success([
            'medias' => AlbumLocationMediaResource::collection($albumLocationMedias)
        ]);
    }

    public function updateAlbumLocationMedia(UpdateAlbumLocationMediaRequest $request, int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $user = app('currentUser');
        $dataUpload = $request->only(['file', 'description', 'created_time', 'information', 'media_type', 'latest_updated_at']);
        $forceUpdate = $request->header('force_update', 0);
        $forceUpdate = (bool)$forceUpdate;
        $albumLocationMedia = $this->_uploadService->updateAlbumLocationMedia($user->company_id,$user->id, $albumId, $albumLocationId, $albumLocationMediaId, $dataUpload, $forceUpdate);
        return Response::success([
            'media' => new AlbumLocationMediaResource($albumLocationMedia)
        ]);
    }

    public function updateImageBeforeAlbumLocationMedia(UpdateImageAlbumLocationMediaRequest $request, int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $dataUpload = $request->only(['file', 'action_type']);
        $albumLocationMedia = $this->_uploadService->updateImageBeforeAlbumLocationMedia($albumId, $albumLocationId, $albumLocationMediaId, $dataUpload);
        return Response::success([
            'media' => new AlbumLocationMediaResource($albumLocationMedia)
        ]);
    }

    public function updateImageAfterAlbumLocationMedia(UpdateImageAlbumLocationMediaRequest $request, int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $dataUpload = $request->only(['file', 'action_type']);
        $albumLocationMedia = $this->_uploadService->updateImageAfterAlbumLocationMedia($albumId, $albumLocationId, $albumLocationMediaId, $dataUpload);
        return Response::success([
            'media' => new AlbumLocationMediaResource($albumLocationMedia)
        ]);
    }

    public function swapAfterBeforeImageAlbum(int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $albumLocationMedia = $this->_uploadService->swapAfterBeforeImageAlbum($albumId, $albumLocationId, $albumLocationMediaId);
        return Response::success([
            'media' => new AlbumLocationMediaResource($albumLocationMedia)
        ]);
    }

    public function deleteImageAfterAlbumLocationMedia(int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $this->_uploadService->deleteImageAfterAlbumLocationMedia($albumId, $albumLocationId, $albumLocationMediaId);
        return Response::success();
    }

    public function uploadAlbumPdf(UploadAlbumPdfRequest $request): JsonResponse
    {
        $files = $this->_uploadService->uploadPdfFiles($request->file('files'), app('currentUser')->id);

        if (!$files) {
            return Response::failure('messages.system_error');
        }

        return Response::success([
            'files' => PdfFileResource::collection($files),
        ]);
    }
}
