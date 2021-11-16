<?php

namespace App\Services\AppService;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceConflictException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use Illuminate\Support\Arr;

class AlbumLocationService extends \App\Services\AlbumLocationService
{
    public function createAlbumLocations(Array $albumLocationData, int $albumId, int $companyId)
    {
        $albumEntity = $this->_albumRepository->find($albumId);
        if (!$albumEntity) {
            throw new NotFoundException('messages.album_does_not_exist');
        }

        $locations = $albumLocationData['locations'];
        $locationTitles = collect($locations)->pluck('title')->all();

        if (count($locationTitles) != count(array_unique($locationTitles))) {
            throw new UnprocessableException('messages.album_location_title_is_incorrect');
        }

        foreach ($locations as $location) {
            if (isset($location['information']) && !empty($location['information'])) {
                $this->_validateInsertLocationInformation($location['information'], $companyId);
            }
        }

        try {
            $this->beginTransaction();
            $albumLocationEntities = collect([]);
            foreach ($locations as $location) {
                $albumLocationEntity = $albumEntity->albumLocations()->create([
                    'title'         => $location['title'],
                    'description'   => $location['description'] ?? ""
                ]);
                if (isset($location['information']) && !empty($location['information'])) {
                    $albumLocationEntity->locationInformation()->createMany($location['information']);
                }
                $albumLocationEntities = $albumLocationEntities->concat([$albumLocationEntity->load('locationInformation')]);
            }
            $this->commitTransaction();
            return $albumLocationEntities;
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function updateAlbumLocation(Array $albumLocationData, int $locationId, int $albumId, int $companyId, $forceUpdate)
    {
        $currentUser = app('currentUser');
        $albumLocationEntity = $this->_albumLocationRepository->where('album_id', '=', $albumId)->find($locationId);
        if (!$albumLocationEntity) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }
        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumLocationEntity->album)) {
            throw new ForbiddenException('messages.not_have_permission');
        }
        $latestUpdatedAt = Arr::pull($albumLocationData, 'latest_updated_at');
        if ($latestUpdatedAt) {
            $latestUpdatedAt = \Illuminate\Support\Carbon::parse($latestUpdatedAt);
        }
        if ($forceUpdate == true || !$latestUpdatedAt || $latestUpdatedAt->greaterThanOrEqualTo($albumLocationEntity->updated_at)) {
            if (isset($albumLocationData['information']) && !empty($albumLocationData['information'])) {
                $this->_validateUpdateOrInsertAlbumLocationInformation($albumLocationData['information'], $companyId);
            }

            if (isset($albumLocationData['medias']) && !empty($albumLocationData['medias'])) {
                foreach ($albumLocationData['medias'] as $media) {
                    $this->_albumLocationMediaService->checkValidateUpdateOrInsertLocationMediaInformation($media['information'], $companyId);
                }
            }

            try {
                $this->beginTransaction();

                if (array_key_exists('description', $albumLocationData)) {
                    $albumLocationEntity = tap($albumLocationEntity)->update([
                        'description' => $albumLocationData['description']
                    ]);
                }
                if (isset($albumLocationData['information']) && !empty($albumLocationData['information'])) {
                    foreach ($albumLocationData['information'] as $information) {
                        $albumLocationEntity->locationInformation()->updateOrCreate(
                            ['location_property_id' => $information['location_property_id']],
                            $information
                        );
                    }
                }
                if (isset($albumLocationData['medias']) && !empty($albumLocationData['medias'])) {
                    $this->_albumLocationMediaService->updateAlbumLocationMedias($albumLocationEntity, $albumLocationData['medias']);
                }
                $this->commitTransaction();
                return $albumLocationEntity->fresh(['locationInformation', 'albumLocationMedias.mediaInformation']);
            } catch (\Exception $exception) {
                $this->rollbackTransaction();
                report($exception);
                throw new SystemException('messages.system_error');
            }
        } else {
            throw new ResourceConflictException('');
        }
    }
}
