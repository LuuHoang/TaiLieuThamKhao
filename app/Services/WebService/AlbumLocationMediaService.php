<?php

namespace App\Services\WebService;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;

class AlbumLocationMediaService extends \App\Services\AlbumLocationMediaService
{
    public function changeLocationOfMedia(int $albumId, int $albumLocationId, int $albumLocationMediaId, string $locationTitle)
    {
        $currentUser = app('currentUser');
        $albumLocationMediaEntity = $this->_albumLocationMediaRepository
            ->where('album_location_id', '=', $albumLocationId)
            ->find($albumLocationMediaId);
        if ($albumLocationMediaEntity == null || $albumLocationMediaEntity->albumLocation->album_id != $albumId) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumLocationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.not_have_permission');
        }
        try {
            $this->beginTransaction();
            $albumEntity = $albumLocationMediaEntity->albumLocation->album;
            $locationEntity = $albumEntity->albumLocations->where('title', $locationTitle)->first();
            if (!$locationEntity) {
                $locationEntity = $albumEntity->albumLocations()->create(['title' => $locationTitle]);
            }
            $albumLocationMediaEntity->update(['album_location_id' => $locationEntity->id]);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
}
