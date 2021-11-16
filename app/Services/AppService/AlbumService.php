<?php

namespace App\Services\AppService;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceConflictException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Utils\Util;
use Illuminate\Support\Arr;

class AlbumService extends \App\Services\AlbumService
{
    public function updateAlbum(Array $albumData, int $albumId, int $userId, int $companyId, $forceUpdate)
    {
        try {
            $currentUser = app('currentUser');
            $latestUpdatedAt = Arr::pull($albumData, 'latest_updated_at');
            if ($latestUpdatedAt) {
                $latestUpdatedAt = \Illuminate\Support\Carbon::parse($latestUpdatedAt);
            }
            $album = $this->_albumRepository->find($albumId);
            if ($album == null) {
                throw new NotFoundException('messages.album_does_not_exist');
            }
            if (!$this->_commonService->allowUpdateAlbum($currentUser, $album)) {
                throw new ForbiddenException('messages.not_have_permission');
            }
            if ($forceUpdate == true || !$latestUpdatedAt || $latestUpdatedAt->greaterThanOrEqualTo($album->updated_at)) {
                $this->beginTransaction();
                $albumEntity = $this->_updateAlbumInformation($albumData, $albumId, $userId, $companyId);
                if (!empty($albumData['locations'])) {
                    $this->_albumLocationService->updateOrInsertAlbumLocations($albumEntity, $albumData['locations'], $companyId);
                }
                $this->commitTransaction();
                Util::setPdfFilesByUser($album->user_id);
                return $this->getAlbumDetail($albumId);
            } else {
                throw new ResourceConflictException('');
            }
        } catch (ResourceConflictException $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw $exception;
        } catch (NotFoundException $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw $exception;
        } catch (ForbiddenException $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw $exception;
        } catch (UnprocessableException $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw $exception;
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
}
