<?php

namespace App\Services\WebService;

use App\Constants\Boolean;
use App\Constants\Disk;
use App\Constants\Media;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceConflictException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Models\AlbumLocationMediaModel;
use App\Repositories\Criteria\SearchSharedAlbumCriteria;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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

    public function getSharedAlbum(Array $dataRequest)
    {
        $sharedAlbumEntity = $this->_sharedAlbum->with(['album'])->where('token', '=', $dataRequest['token'])->first();

        if ($sharedAlbumEntity == null) {
            throw new UnprocessableException('messages.session_has_expired');
        }

        if ($sharedAlbumEntity->status == Boolean::FALSE) {
            throw new ForbiddenException('messages.album_sharing_link_has_expired');
        }

        if (!Hash::check($dataRequest['password'], $sharedAlbumEntity->password)) {
            throw new ForbiddenException('messages.password_is_incorrect');
        }

        return $sharedAlbumEntity->album;
    }

    public function getListSharedAlbums(int $userId, Array $paramQuery, int $limit)
    {
        return $this->_sharedAlbum
            ->pushCriteria(new SearchSharedAlbumCriteria($paramQuery))
            ->where('user_id', '=', $userId)
            ->paginate($limit);
    }

    public function changeStatusSharedAlbum(int $sharedAlbumId)
    {
        $userId = app('currentUser')->id;
        $sharedAlbumEntity = $this->_sharedAlbum->find($sharedAlbumId);

        if ($sharedAlbumEntity == null) {
            throw new NotFoundException('messages.album_sharing_link_does_not_exist');
        }

        if ($sharedAlbumEntity->user_id != $userId) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if ($sharedAlbumEntity->status == Boolean::TRUE) {
            $sharedAlbumEntity->status = Boolean::FALSE;
        } elseif ($sharedAlbumEntity->status == Boolean::FALSE) {
            $sharedAlbumEntity->status = Boolean::TRUE;
        }

        $sharedAlbumEntity->save();
        return $sharedAlbumEntity;
    }

    public function getConfigNameAlbum(int $albumId)
    {
        $user = app('currentUser');
        $albumEntity = $this->_albumRepository->find($albumId);

        if($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }
        $arrayNameConfig = json_decode($albumEntity->config, true);
        return $this->_handleConfigNameAlbum($arrayNameConfig);
    }

    public function setConfigNameAlbum(int $albumId, Array $configData = null)
    {
        $user = app('currentUser');
        $albumEntity = $this->_albumRepository->find($albumId);

        if($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }
        if (!$this->_commonService->allowUpdateAlbum($user, $albumEntity)) {
            throw new ForbiddenException('messages.not_have_permission');
        }
        if (!empty($configData)) {
            $configData = json_encode($configData);
        }
        $albumEntity->update([
            'config'    =>  $configData
        ]);
        $arrayNameConfig = json_decode($albumEntity->config, true);
        return $this->_handleConfigNameAlbum($arrayNameConfig);
    }

    private function _handleConfigNameAlbum(Array $arrayNameConfig = null)
    {
        $user = app('currentUser');
        $properties = $this->_albumPropertyRepository
            ->where('company_id', '=', $user->company_id)
            ->all(['id', 'title']);
        if (!empty($arrayNameConfig)) {
            foreach ($arrayNameConfig as $key => $nameConfig) {
                if (!empty($nameConfig['id'])) {
                    $propertyConfig = $properties->find($nameConfig['id']);
                    if ($propertyConfig != null) {
                        $arrayNameConfig[$key]['title'] = $propertyConfig->title;
                    } else {
                        $arrayNameConfig[$key]['title'] = null;
                    }
                }
            }
            $arrayNameConfig = array_filter($arrayNameConfig, function ($config) {
                return !empty($config['title']);
            });
            $arrayNameConfig = array_values($arrayNameConfig);
        } else {
            $arrayNameConfig = [];
        }
        return [
            'data_config'   =>  $arrayNameConfig,
            'data_properties'   =>  $properties
        ];
    }

    public function downloadMediaByNameConfig(int $albumId, Array $mediaDataIds)
    {
        $albumEntity = $this->_albumRepository
            ->with(['albumInformations', 'albumLocations'])
            ->find($albumId);
        $locationIds = $albumEntity->albumLocations->pluck('id')->toArray();
        $albumLocationMediaEntities = $this->_albumLocationMediaRepository
            ->whereIn('album_location_id', $locationIds)
            ->whereIn('id', $mediaDataIds)
            ->all();
        if ($albumLocationMediaEntities->count() == 0) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
        $fileName = $this->_generateNameFileDownload(json_decode($albumEntity->config, true), $albumId, $albumEntity->albumInformations);
        if ($albumLocationMediaEntities->count() == 1) {
            $mediaDownload = $this->_generateUrlMediaFileDownload($fileName, $albumLocationMediaEntities[0]);
            $urlDownload = $mediaDownload['url'];
            $imageAfterUrl = $mediaDownload['image_after_url'];
        } else {
            $urlDownload = $this->_generateUrlZipFileDownload($fileName, $albumLocationMediaEntities);
            $imageAfterUrl = '';
        }
        return [
            'url'   =>$urlDownload,
            'image_after_url' => $imageAfterUrl
        ];
    }

    private function _generateNameFileDownload(Array $nameConfig = null, int $albumId, Collection $albumInformation)
    {
        if (!empty($nameConfig)) {
            $name = null;
            $arrAlbumInformation = [];
            foreach ($albumInformation as $information) {
                $arrAlbumInformation[$information->album_property_id] = $information;
            }
            foreach ($nameConfig as $itemConfig) {
                if (!empty($itemConfig['id'])) {
                    if (!empty($arrAlbumInformation[$itemConfig['id']])) {
                        $name .= str_replace(' ', '_', $arrAlbumInformation[$itemConfig['id']]->value) . '_';
                    }
                } else {
                    $name .= str_replace(' ', '_', $itemConfig['title']) . '_';
                }
            }
            $name = rtrim($name, '_');
        } else {
            $name = $albumId. '_' . time();
        }
        return $name;
    }

    private function _generateUrlMediaFileDownload (String $fileName, AlbumLocationMediaModel $albumLocationMedia)
    {
        $fileExtension = $this->_getExtension($albumLocationMedia->path, $albumLocationMedia->type);
        $fileName = $fileName . '.' . $fileExtension;
        $fileContent = null;
        if($albumLocationMedia->type == Media::TYPE_IMAGE) {
            $fileContent = Storage::disk(Disk::ALBUM)->get($albumLocationMedia->path);
        } elseif ($albumLocationMedia->type == Media::TYPE_VIDEO) {
            $fileContent = Storage::disk(Disk::ALBUM)->get($albumLocationMedia->path);
        }
        $status = Storage::disk(Disk::DOWNLOAD)->put($fileName, $fileContent);
        if (!$status) {
            throw new SystemException('messages.system_error');
        }
        return [
            'url' => Storage::disk(Disk::DOWNLOAD)->url($fileName),
            'image_after_url' => Storage::disk(Disk::ALBUM)->url($albumLocationMedia->image_after_path)
        ];
    }

    private function _generateUrlZipFileDownload (String $fileName, Collection $albumLocationMedias)
    {
        $zip = new ZipArchive();
        $fileNameDownload = $fileName . '.zip';
        $zipPath = Storage::disk(Disk::DOWNLOAD)->path($fileNameDownload);
        if ($zip->open($zipPath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
            throw new SystemException('messages.system_error');
        }
        foreach ($albumLocationMedias as $key => $albumLocationMedia) {
            $fileExtension = $this->_getExtension($albumLocationMedia->path, $albumLocationMedia->type);
            $filePath = null;
            if($albumLocationMedia->type == Media::TYPE_IMAGE) {
                $filePath = Storage::disk(Disk::ALBUM)->path($albumLocationMedia->path);
            } elseif ($albumLocationMedia->type == Media::TYPE_VIDEO) {
                $filePath = Storage::disk(Disk::ALBUM)->path($albumLocationMedia->path);
            }
            $zip->addFile($filePath, $fileName . '(' . $key . ')' . '.' . $fileExtension);
        }
        $zip->close();
        return Storage::disk(Disk::DOWNLOAD)->url($fileNameDownload);
    }

    private function _getExtension(String $filePath, int $mediaType) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION );
        if ($mediaType == Media::TYPE_IMAGE) {
            if (!in_array($extension, Media::IMAGE_EXTENSION)) {
                $extension = 'png';
            }
        }
        return $extension;
    }

    public function getEmailOfShareUser(array $shareUserData)
    {
        $sharedAlbumEntity = $this->_sharedAlbum->with(['album'])->where('token', '=', $shareUserData['token'])->first();

        if ($sharedAlbumEntity == null) {
            throw new UnprocessableException('messages.session_has_expired');
        }

        if ($sharedAlbumEntity->status == Boolean::FALSE) {
            throw new ForbiddenException('messages.album_sharing_link_has_expired');
        }

        if (!Hash::check($shareUserData['password'], $sharedAlbumEntity->password)) {
            throw new ForbiddenException('messages.password_is_incorrect');
        }

        return $sharedAlbumEntity->email;
    }
}
