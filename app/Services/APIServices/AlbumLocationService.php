<?php

namespace App\Services\APIServices;

use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Models\AlbumLocationMediaModel;
use App\Models\AlbumLocationModel;
use App\Models\AlbumModel;
use App\Repositories\Repository;
use App\Services\AbstractService;

class AlbumLocationService extends AbstractService
{
    private $_albumRepository;
    private $_albumLocationRepository;
    private $_albumLocationMediaRepository;

    public function __construct(AlbumLocationModel $albumLocationModel, AlbumLocationMediaModel $albumLocationMediaModel, AlbumModel $albumModel)
    {
        $this->_albumRepository = new Repository($albumModel);
        $this->_albumLocationRepository = new Repository($albumLocationModel);
        $this->_albumLocationMediaRepository = new Repository($albumLocationMediaModel);
    }

    public function editCommentAlbumLocation(string $comment = null, int $albumId, int $albumLocationId, int $userId)
    {
        $albumLocationEntity = $this->_albumLocationRepository
            ->where('album_id', '=', $albumId)
            ->find($albumLocationId);

        if ($albumLocationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }
        if ($albumLocationEntity->album->user_id != $userId) {
            throw new UnprocessableException('messages.not_have_permission');
        }

        $albumLocationEntity->comment = $comment;
        $albumLocationEntity->save();
    }

    public function editCommentAlbumLocationMedia(string $comment = null, int $albumId, int $albumLocationId, int $albumLocationMediaId, int $userId)
    {
        $albumLocationMediaEntity = $this->_albumLocationMediaRepository
            ->where('album_location_id', '=', $albumLocationId)
            ->find($albumLocationMediaId);

        if ($albumLocationMediaEntity == null || $albumLocationMediaEntity->albumLocation->album_id != $albumId) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
        if ($albumLocationMediaEntity->albumLocation->album->user_id != $userId) {
            throw new UnprocessableException('messages.not_have_permission');
        }

        $albumLocationMediaEntity->comment = $comment;
        $albumLocationMediaEntity->save();
    }

    public function checkModifyLocation(int $albumId, int $locationId, string $updatedAt)
    {
        $albumLocationEntity = $this->_albumLocationRepository
            ->where('album_id', '=', $albumId)
            ->find($locationId);

        if ($albumLocationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }
        try {
            $status = false;
            $updatedAt = \Illuminate\Support\Carbon::parse($updatedAt);
            if ($updatedAt->lessThan($albumLocationEntity->updated_at)) {
                $status = true;
            }
            return ['modify' => $status];
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function checkModifyMedia(int $albumId, int $locationId, int $mediaId, string $updatedAt)
    {
        $albumLocationMediaEntity = $this->_albumLocationMediaRepository
            ->where('album_location_id', '=', $locationId)
            ->find($mediaId);

        if ($albumLocationMediaEntity == null || $albumLocationMediaEntity->albumLocation->album_id != $albumId) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
        try {
            $status = false;
            $updatedAt = \Illuminate\Support\Carbon::parse($updatedAt);
            if ($updatedAt->lessThan($albumLocationMediaEntity->updated_at)) {
                $status = true;
            }
            return ['modify' => $status];
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
}
