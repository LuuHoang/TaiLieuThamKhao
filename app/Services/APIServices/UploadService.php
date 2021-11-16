<?php

namespace App\Services\APIServices;

use App\Constants\Disk;
use App\Constants\Media;
use App\Constants\StampType;
use App\Exceptions\ForbiddenException;
use App\Exceptions\ResourceConflictException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Models\AbstractModel;
use App\Models\PdfFileModel;
use App\Models\UserUsageModel;
use App\Services\AbstractService;
use App\Repositories\Repository;
use App\Services\AlbumLocationMediaService;
use App\Services\CommonService;
use App\Services\DataUsageStatisticService;
use App\Services\UploadMediaService;
use App\Supports\Facades\Image\ImageUtil;
use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\AlbumLocationMediaModel;
use App\Models\AlbumLocationModel;
use App\Models\AlbumModel;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class UploadService extends AbstractService
{

    /**
     * @var Repository
     */
    protected $_userRepository;

    /**
     * @var Repository
     */
    protected $_userUsageRepository;

    /**
     * @var Repository
     */
    protected $_albumLocationMediaRepository;

    /**
     * @var Repository
     */
    protected $_albumLocationRepository;

    /**
     * @var Repository
     */
    protected $_albumRepository;

    protected $_pdfFileRepository;

    protected $_uploadMediaService;
    protected $_dataUsageStatisticService;
    protected $_albumLocationMediaService;
    protected $_commonService;

    public function __construct(
        UserModel $userModel,
        UserUsageModel $userUsageModel,
        AlbumLocationMediaModel $albumLocationMediaModel,
        AlbumLocationModel $albumLocationModel,
        AlbumModel $albumModel,
        PdfFileModel $pdfFileModel,
        UploadMediaService $uploadMediaService,
        DataUsageStatisticService $dataUsageStatisticService,
        AlbumLocationMediaService $albumLocationMediaService,
        CommonService $commonService
    )
    {
        $this->_userRepository = new Repository($userModel);
        $this->_userUsageRepository = new Repository($userUsageModel);
        $this->_albumLocationMediaRepository = new Repository($albumLocationMediaModel);
        $this->_albumLocationRepository = new Repository($albumLocationModel);
        $this->_albumRepository = new Repository($albumModel);
        $this->_uploadMediaService = $uploadMediaService;
        $this->_dataUsageStatisticService = $dataUsageStatisticService;
        $this->_albumLocationMediaService = $albumLocationMediaService;
        $this->_commonService = $commonService;
        $this->_pdfFileRepository = new Repository($pdfFileModel);
    }

    public function uploadAvatar(Request $request, $userId)
    {
        try {
            $image = $request->file('file');
            $fileName = $userId . '-' . $this->_getImageName($image);
            $uploadStatus = Storage::disk(Disk::USER)->put($fileName, file_get_contents($image));
            if (!$uploadStatus) {
                return null;
            }
            ImageUtil::addStampToImage(Storage::disk('public')->path(Disk::USER.'/'. $fileName));
            return $fileName;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Resize an image and keep the proportions
     * @param integer $max_width
     * @param integer $max_height
     * @return false|resource
     * @author Allison Beckwith <allison@planetargon.com>
     */
    function getImageStamp($max_width, $max_height)
    {
        $config = app('currentUser')->company->config;
        $filename = Storage::disk(Disk::COMPANY)->path($config->icon_path);

        [$orig_width, $orig_height] = getimagesize($filename);

        $width = $orig_width;
        $height = $orig_height;

        # taller
        if ($height > $max_height) {
            $width = ($max_height / $height) * $width;
            $height = $max_height;
        }

        # wider
        if ($width > $max_width) {
            $width = $max_width;
            $height = ($max_width / $width) * $height;
        }

        $image_p = imagecreatetruecolor($width, $height);
        $alpha_channel = imagecolorallocatealpha($image_p, 0, 0, 0, 127);
        imagecolortransparent($image_p, $alpha_channel);
        // Fill image
        imagefill($image_p, 0, 0, $alpha_channel);

        $image = imagecreatefrompng($filename);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0,
            $width, $height, $orig_width, $orig_height);

        return $image_p;
    }

    public function addStampToImage(string $imagePath)
    {
        try {
            // Load the stamp and the photo to apply the watermark to
            header('Content-Type: image/jpeg');
            $im = imagecreatefromjpeg($imagePath);

            # config orientation
            $exif = exif_read_data($imagePath);
            # Get orientation
            $orientation = $exif['Orientation'] ?? 1;
            # Manipulate image
            switch ($orientation) {
                case 2:
                    imageflip($im, IMG_FLIP_HORIZONTAL);
                    break;
                case 3:
                    $im = imagerotate($im, 180, 0);
                    break;
                case 4:
                    imageflip($im, IMG_FLIP_VERTICAL);
                    break;
                case 5:
                    $im = imagerotate($im, -90, 0);
                    imageflip($im, IMG_FLIP_HORIZONTAL);
                    break;
                case 6:
                    $im = imagerotate($im, -90, 0);
                    break;
                case 7:
                    $im = imagerotate($im, 90, 0);
                    imageflip($im, IMG_FLIP_HORIZONTAL);
                    break;
                case 8:
                    $im = imagerotate($im, 90, 0);
                    break;
            }

            // Set the margins for the stamp and get the height/width of the stamp image
            $imWidth = imagesx($im);
            $imHeight = imagesy($im);

            $config = $config = app('currentUser')->company->config;
            if ($config->stamp_type === StampType::ICON) {
                $stamp = $this->getImageStamp($imWidth * 0.2, $imHeight * 0.2);

                // Copy the stamp image onto our photo using the margin offsets and the photo
                // width to calculate positioning of the stamp.
                $coordinates = $this->getCoordinates($config->mounting_position, ['x' => $imWidth, 'y' => $imHeight], ['x' => imagesx($stamp), 'y' => imagesy($stamp)]);

                imagecopy($im, $stamp, $coordinates['x'], $coordinates['y'], 0, 0, imagesx($stamp), imagesy($stamp));
                imagejpeg($im, $imagePath);
            }

            if ($config->stamp_type === 1) {
                $font = public_path('/fonts/NotoSansRegular.otf');
                $text = $config->text;
                $textInfo = $this->getTextInformation($text, $font);
                $info = $this->getCoordinates($config->mounting_position, ['x' => $imWidth, 'y' => $imHeight], ['x' => $textInfo['width'], 'y' => $textInfo['height']], true);
                $white = imagecolorallocate($im, 255, 0, 0);

                imagettftext($im, 24, 0, $info['x'], $info['y'], $white, $font, $text);
                imagejpeg($im, $imagePath, 100);
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function uploadAlbumAvatar(Request $request, $userId)
    {
        try {
            $image = $request->file('file');
            $fileName = $userId . '-' . $this->_getImageName($image);
            $uploadStatus = Storage::disk(Disk::IMAGE)->put($fileName, file_get_contents($image));
            if (!$uploadStatus) {
                return null;
            }
            ImageUtil::addStampToImage(Storage::disk('public')->path(Disk::IMAGE.'/'. $fileName));
            return $fileName;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    private function _getImageName($image)
    {
        $extension = str_replace('image/', '', $image->getMimeType());
        return Carbon::now(env('TIMEZONE'))->format('Y-m-d_H-i-s-u') . '.' . $extension;
    }

    public function uploadAlbumImages($images, $mediaType, $userId)
    {
        $imageList = [];
        if (!count($images)) {
            throw new UnprocessableException('messages.not_selected_file_upload');
        }
        foreach ($images as $key => $image) {
            try {
                $fileSize = $image->getSize();
                $imagePath = $this->_handleImage($image);
                $imageData = [
                    'path' => $imagePath,
                    'url' => Storage::disk(Disk::IMAGE)->url($imagePath),
                    'created_user' => $userId,
                    'type' => $mediaType,
                    'status' => Media::UPLOAD_SUCCESS_STATUS,
                    'size' => $fileSize,
                ];
                array_push($imageList, $imageData);
            } catch (\Exception $e) {
                report($e);
                array_push($imageList, [
                    'path' => null,
                    'url' => null,
                    'created_user' => $userId,
                    'type' => $mediaType,
                    'status' => Media::UPLOAD_ERROR_STATUS,
                    'size' => 0
                ]);
            }
        }
        return $imageList;
    }

    private function _handleImage($image)
    {
        $fileName = $this->_getImageName($image);
        Storage::disk(Disk::IMAGE)->put($fileName, file_get_contents($image));
        ImageUtil::addStampToImage(Storage::disk('public')->path(Disk::IMAGE.'/'. $fileName));
        return $fileName;
    }

    public function uploadAlbumVideos($videos, $mediaType, $userId)
    {
        $videoList = [];
        if (!count($videos)) {
            throw new UnprocessableException('messages.not_selected_file_upload');
        }

        foreach ($videos as $key => $video) {
            try {
                $fileSize = $video->getSize();
                $videoInfo = $this->_getVideoInfo($video);
                $video = $this->_handleVideo($videoInfo, $video);
                $videoData = [
                    'path' => $video['path'],
                    'url' => Storage::disk(Disk::VIDEO)->url($video['path']),
                    'thumbnail_path' => $video['thumbnail_path'],
                    'thumbnail_url' => Storage::disk(Disk::THUMBNAIL)->url($video['thumbnail_path']),
                    'created_user' => $userId,
                    'type' => $mediaType,
                    'status' => Media::UPLOAD_SUCCESS_STATUS,
                    'size' => $fileSize,
                ];

                array_push($videoList, $videoData);
            } catch (\Exception $e) {
                report($e);
                return [
                    'path' => "",
                    'url' => "",
                    'thumbnail_path' => "",
                    'thumbnail_url' => "",
                    'created_user' => $userId,
                    'type' => $mediaType,
                    'status' => Media::UPLOAD_ERROR_STATUS,
                    'size' => "",
                ];
            }
        }
        return $videoList;
    }

    public function _getVideoInfo($file): array
    {
        if ($file && $file->isValid()) {
            $extension = $file->getClientOriginalExtension();
            $fileName["name"] = md5(microtime(true));
            $fileName["ext"] = $extension;
            return $fileName;
        }

        return array();
    }

    private function _handleVideo(array $videoInfo, $video): array
    {
        try {
            $fileName = implode('.', $videoInfo);
            $thumbnailName = "{$videoInfo["name"]}_thumb.png";
            Storage::disk(Disk::VIDEO)->put($fileName, file_get_contents($video));
            $videoTargetPath = storage_path('app/public/') . Disk::VIDEO . '/' . $fileName;
            $targetThumbnailPath = storage_path('app/public/') . Disk::THUMBNAIL . '/' . $thumbnailName;

            // Generate thumbnail
            exec("ffmpeg -i $videoTargetPath -deinterlace -an -ss 1  -t 00:00:01 -r 1 -y -vcodec mjpeg $targetThumbnailPath 2>&1");

            ImageUtil::addStampToImage(Storage::disk('public')->path(Disk::THUMBNAIL.'/'.$thumbnailName));
            return [
                "path" => $fileName,
                "thumbnail_path" => $thumbnailName,
                "size" => Storage::disk(Disk::VIDEO)->size($fileName)
            ];

        } catch (\Exception $e) {
            report($e);
        }
    }

    public function allowedToUpload(int $userId, int $fileSize)
    {
        $userEntity = $this->_userRepository->find($userId);
        if ($userEntity == null) {
            throw new NotFoundException('messages.user_does_not_exist');
        }
        $userCreated = null;
        if ($this->_commonService->isSubUser(json_decode($userEntity->userRole->permissions ?? '[]', true))) {
            $userCreated = $userEntity->userCreated;
        } else {
            $userCreated = $userEntity;
        }
        $subUsers = $userCreated->subUsers;
        $countData = $userCreated->userUsage->count_data;
        if ($subUsers->isNotEmpty()) {
            foreach ($subUsers as $subUser) {
                $countData += $subUser->userUsage->count_data;
            }
        }
        $maxAllow = $userCreated->userUsage->package_data + $userCreated->userUsage->extend_data;

        if ($countData + $fileSize > $maxAllow) {
            return false;
        }

        return true;
    }

    private function _deleteMediaInStorage(AlbumLocationMediaModel $albumLocationMediaEntity)
    {
        if ($albumLocationMediaEntity->type == Media::TYPE_IMAGE) {
            Storage::disk(Disk::ALBUM)->delete($albumLocationMediaEntity->path);
        } elseif ($albumLocationMediaEntity->type == Media::TYPE_VIDEO) {
            Storage::disk(Disk::ALBUM)->delete($albumLocationMediaEntity->path);
            Storage::disk(Disk::ALBUM)->delete($albumLocationMediaEntity->thumbnail_path);
        }
    }

    public function insertAlbumLocationMedias(int $companyId, int $userId, int $albumId, int $albumLocationId, Array $dataUpload)
    {
        $currentUser = app('currentUser');
        $albumEntity = $this->_albumRepository
            ->with(['user.userUsage', 'user.company.companyUsage', 'albumLocations'])
            ->find($albumId);
        if ($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }
        $albumLocationEntity = $albumEntity->albumLocations->find($albumLocationId);
        if ($albumLocationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }
        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumEntity)) {
            throw new ForbiddenException('messages.not_have_permission');
        }
        $userEntity = $albumEntity->user;
        $this->_uploadMediaService->checkAllowToUploadMedia($userEntity, $this->_getTotalSize($dataUpload['medias']));
        foreach ($dataUpload['medias'] as $media) {
            if (isset($media['information']) && !empty($media['information'])) {
                $this->_albumLocationMediaService->checkValidateInsertLocationMediaInformation($media['information'], $companyId);
            }
        }
        if ($dataUpload['media_type'] == Media::TYPE_IMAGE) {
            return $this->_handleAlbumLocationImages($albumLocationEntity, $dataUpload['medias']);
        } elseif ($dataUpload['media_type'] == Media::TYPE_VIDEO) {
            return $this->_handleAlbumLocationVideos($albumLocationEntity, $dataUpload['medias']);
        } else {
            return null;
        }
    }

    private function _getTotalSize(Array $medias)
    {
        $totalSize = 0;
        foreach ($medias as $media) {
            $totalSize += $media['file']->getSize();
        }
        return $totalSize;
    }

    private function _handleAlbumLocationImages(AlbumLocationModel $albumLocation, Array $medias)
    {
        $dataImages = [];
        $totalSize = 0;
        $album = $albumLocation->album;
        $folder = $album->user_id . '/' . $album->id . '/' . $albumLocation->id;
        foreach ($medias as $media) {
            $fileName = $this->_uploadMediaService->uploadImageAlbum($media['file'], $folder);
            $dataImages[] = [
                'information'   =>  $media['information'] ?? null,
                'description'   =>  $media['description'] ?? "",
                'created_time'  =>  $media['created_time'] ?? now()->format('H:i:s d/m/Y'),
                'path'          =>  $fileName,
                'type'          =>  Media::TYPE_IMAGE,
                'before_size'   =>  $media['file']->getSize(),
                'size'          =>  $media['file']->getSize()
            ];
            $totalSize += $media['file']->getSize();
        }
        return $this->_insertAlbumLocationMedias($albumLocation, $dataImages, $totalSize);
    }

    private function _handleAlbumLocationVideos(AlbumLocationModel $albumLocation, Array $medias)
    {
        $dataVideos = [];
        $totalSize = 0;
        $album = $albumLocation->album;
        $folder = $album->user_id . '/' . $album->id . '/' . $albumLocation->id;
        foreach ($medias as $media) {
            $videoInfo = $this->_uploadMediaService->UploadVideoAlbum($media['file'], $folder);
            $dataVideos[] = [
                'information'       =>  $media['information'] ?? null,
                'description'       =>  $media['description'] ?? "",
                'created_time'      =>  $media['created_time'] ?? now()->format('H:i:s d/m/Y'),
                'path'              =>  $videoInfo['file_name'],
                'thumbnail_path'    =>  $videoInfo['file_thumb_name'],
                'type'              =>  Media::TYPE_VIDEO,
                'before_size'       =>  $media['file']->getSize(),
                'size'              =>  $media['file']->getSize()
            ];
            $totalSize += $media['file']->getSize();
        }
        return $this->_insertAlbumLocationMedias($albumLocation, $dataVideos, $totalSize);
    }

    private function _insertAlbumLocationMedias(AlbumLocationModel $albumLocation, Array $mediaData, int $totalSize)
    {
        try {
            $this->beginTransaction();
            $albumLocationMedias = collect([]);
            foreach ($mediaData as $data) {
                $mediaInformation = Arr::pull($data, 'information');
                $albumLocationMediaEntity = $albumLocation->albumLocationMedias()->create($data);
                if ($mediaInformation != null) {
                    $albumLocationMediaEntity->mediaInformation()->createMany($mediaInformation);
                    $albumLocationMediaEntity = $albumLocationMediaEntity->load('mediaInformation');
                }
                $albumLocationMedias = $albumLocationMedias->concat([$albumLocationMediaEntity]);
            }
            $this->_dataUsageStatisticService->updateAlbumSize($albumLocation->album, $totalSize);
            $this->_dataUsageStatisticService->updateCountDataUserUsage($albumLocation->album->user->userUsage, $totalSize);
            $this->_dataUsageStatisticService->updateCountDataCompanyUsage($albumLocation->album->user->company->companyUsage, $totalSize);
            $this->commitTransaction();
            return $albumLocationMedias;
        } catch (\Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function updateAlbumLocationMedia(int $companyId, int $userId, int $albumId, int $albumLocationId, int $albumLocationMediaId, Array $dataUpload, $forceUpdate)
    {
        $currentUser = app('currentUser');
        $albumLocationMediaEntity = $this->_albumLocationMediaRepository
            ->with(['albumLocation.album.user.userUsage', 'albumLocation.album.user.company.companyUsage'])
            ->find($albumLocationMediaId);
        if ($albumLocationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
        if ($albumLocationMediaEntity->albumLocation->id != $albumLocationId) {
            throw new UnprocessableException('messages.album_location_is_incorrect');
        }
        if ($albumLocationMediaEntity->albumLocation->album->id != $albumId) {
            throw new UnprocessableException('messages.album_id_is_incorrect');
        }

        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumLocationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        $latestUpdatedAt = Arr::pull($dataUpload, 'latest_updated_at');
        if ($latestUpdatedAt) {
            $latestUpdatedAt = \Illuminate\Support\Carbon::parse($latestUpdatedAt);
        }

        if ($forceUpdate == true || !$latestUpdatedAt || $latestUpdatedAt->greaterThanOrEqualTo($albumLocationMediaEntity->updated_at)) {
            if (isset($dataUpload['file']) && !empty($dataUpload['file'])) {
                $userEntity = $albumLocationMediaEntity->albumLocation->album->user;
                $this->_uploadMediaService->checkAllowToUploadMedia($userEntity, $dataUpload['file']->getSize() - $albumLocationMediaEntity->before_size);
            }

            if (isset($dataUpload['information']) && !empty($dataUpload['information'])) {
                $this->_albumLocationMediaService->checkValidateUpdateOrInsertLocationMediaInformation($dataUpload['information'], $companyId);
            }

            try {
                $this->beginTransaction();

                if (isset($dataUpload['file']) && !empty($dataUpload['file'])) {
                    if ($dataUpload['media_type'] == Media::TYPE_IMAGE) {
                        $this->_handleAlbumLocationImage($albumLocationMediaEntity, $dataUpload['file'], FALSE);
                    } elseif ($dataUpload['media_type'] == Media::TYPE_VIDEO) {
                        $this->_handleAlbumLocationVideo($albumLocationMediaEntity, $dataUpload['file']);
                    }
                }

                $subData = [];
                if (array_key_exists('description', $dataUpload)) {
                    $subData['description'] = $dataUpload['description'];
                }
                if (array_key_exists('created_time', $dataUpload)) {
                    $subData['created_time'] = $dataUpload['created_time'];
                }
                if (!empty($subData)) {
                    $albumLocationMediaEntity->update($subData);
                }

                if (isset($dataUpload['information']) && !empty($dataUpload['information'])) {
                    foreach ($dataUpload['information'] as $information) {
                        $albumLocationMediaEntity->mediaInformation()->updateOrCreate(
                            ['media_property_id' => $information['media_property_id']],
                            $information
                        );
                    }
                }

                $this->commitTransaction();

                return $albumLocationMediaEntity->fresh(['mediaInformation', 'comments', 'albumLocation']);
            } catch (Exception $exception) {
                report($exception);
                $this->rollbackTransaction();
                throw new SystemException('messages.system_error');
            }
        } else {
            throw new ResourceConflictException('');
        }
    }

    private function _handleAlbumLocationImage(AlbumLocationMediaModel $albumLocationMedia, UploadedFile $file, $stamp = TRUE)
    {
        $albumLocation = $albumLocationMedia->albumLocation;
        $album = $albumLocation->album;
        $folder = $album->user_id . '/' . $album->id . '/' . $albumLocation->id;
        $fileName = $this->_uploadMediaService->uploadImageAlbum($file, $folder, $stamp);
        $dataImage = [
            'path'              =>  $fileName,
            'thumbnail_path'    =>  null,
            'type'              =>  Media::TYPE_IMAGE,
            'before_size'       =>  $file->getSize(),
            'size'              =>  $albumLocationMedia->size + $file->getSize() - $albumLocationMedia->before_size
        ];
        $sizeUpdate = $file->getSize() - $albumLocationMedia->before_size;
        return $this->_updateAlbumLocationMedias($albumLocationMedia, $dataImage, $sizeUpdate);
    }

    private function _handleAlbumLocationVideo(AlbumLocationMediaModel $albumLocationMedia, UploadedFile $file)
    {
        $albumLocation = $albumLocationMedia->albumLocation;
        $album = $albumLocation->album;
        $folder = $album->user_id . '/' . $album->id . '/' . $albumLocation->id;
        $videoInfo = $this->_uploadMediaService->UploadVideoAlbum($file, $folder);
        $dataVideo = [
            'path'              =>  $videoInfo['file_name'],
            'thumbnail_path'    =>  $videoInfo['file_thumb_name'],
            'type'              =>  Media::TYPE_VIDEO,
            'before_size'       =>  $file->getSize(),
            'size'              =>  $albumLocationMedia->size + $file->getSize() - $albumLocationMedia->before_size
        ];
        $sizeUpdate = $file->getSize() - $albumLocationMedia->before_size;
        return $this->_updateAlbumLocationMedias($albumLocationMedia, $dataVideo, $sizeUpdate);
    }

    private function _updateAlbumLocationMedias(AlbumLocationMediaModel $albumLocationMedia, Array $mediaData, int $sizeUpdate)
    {
        try {
            $this->beginTransaction();
            $oldAlbumLocationMediaEntity = $albumLocationMedia->replicate();
            $albumLocationMedia->update($mediaData);
            $this->_dataUsageStatisticService->updateAlbumSize($albumLocationMedia->albumLocation->album, $sizeUpdate);
            $this->_dataUsageStatisticService->updateCountDataUserUsage($albumLocationMedia->albumLocation->album->user->userUsage, $sizeUpdate);
            $this->_dataUsageStatisticService->updateCountDataCompanyUsage($albumLocationMedia->albumLocation->album->user->company->companyUsage, $sizeUpdate);
            $this->commitTransaction();
            $this->_deleteMediaInStorage($oldAlbumLocationMediaEntity);
            return $albumLocationMedia;
        } catch (\Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function updateImageBeforeAlbumLocationMedia(int $albumId, int $albumLocationId, int $albumLocationMediaId, Array $dataUpload)
    {
        $currentUser = app('currentUser');
        $albumLocationMediaEntity = $this->_albumLocationMediaRepository
            ->with(['albumLocation.album.user.userUsage', 'albumLocation.album.user.company.companyUsage'])
            ->find($albumLocationMediaId);
        if ($albumLocationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
        if ($albumLocationMediaEntity->albumLocation->id != $albumLocationId) {
            throw new UnprocessableException('messages.album_location_is_incorrect');
        }
        if ($albumLocationMediaEntity->albumLocation->album->id != $albumId) {
            throw new UnprocessableException('messages.album_id_is_incorrect');
        }

        if ($albumLocationMediaEntity->type != Media::TYPE_IMAGE) {
            throw new ForbiddenException('messages.feature_only_supported_image');
        }

        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumLocationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if (isset($dataUpload['file']) && !empty($dataUpload['file'])) {
            $userEntity = $albumLocationMediaEntity->albumLocation->album->user;
            $this->_uploadMediaService->checkAllowToUploadMedia($userEntity, $dataUpload['file']->getSize() - $albumLocationMediaEntity->before_size);
        }

        try {
            $this->beginTransaction();

            if (isset($dataUpload['file']) && !empty($dataUpload['file'])) {
                if (!empty($dataUpload['action_type']) && $dataUpload['action_type'] == Media::ACTION_UPLOAD) {
                    $this->_handleAlbumLocationImage($albumLocationMediaEntity, $dataUpload['file'], true);
                } else if(!empty($dataUpload['action_type']) && $dataUpload['action_type'] == Media::ACTION_UPDATE) {
                    $this->_handleAlbumLocationImage($albumLocationMediaEntity, $dataUpload['file'], false);
                }
            }

            $this->commitTransaction();

            return $albumLocationMediaEntity->fresh(['mediaInformation', 'comments', 'albumLocation']);
        } catch (Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function updateImageAfterAlbumLocationMedia(int $albumId, int $albumLocationId, int $albumLocationMediaId, Array $dataUpload)
    {
        $currentUser = app('currentUser');
        $albumLocationMediaEntity = $this->_albumLocationMediaRepository
            ->with(['albumLocation.album.user.userUsage', 'albumLocation.album.user.company.companyUsage'])
            ->find($albumLocationMediaId);
        if ($albumLocationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
        if ($albumLocationMediaEntity->albumLocation->id != $albumLocationId) {
            throw new UnprocessableException('messages.album_location_is_incorrect');
        }
        if ($albumLocationMediaEntity->albumLocation->album->id != $albumId) {
            throw new UnprocessableException('messages.album_id_is_incorrect');
        }

        if ($albumLocationMediaEntity->type != Media::TYPE_IMAGE) {
            throw new ForbiddenException('messages.feature_only_supported_image');
        }

        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumLocationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if (isset($dataUpload['file']) && !empty($dataUpload['file'])) {
            $userEntity = $albumLocationMediaEntity->albumLocation->album->user;
            $this->_uploadMediaService->checkAllowToUploadMedia($userEntity, $dataUpload['file']->getSize() - $albumLocationMediaEntity->after_size);
        }

        try {
            $this->beginTransaction();

            if (isset($dataUpload['file']) && !empty($dataUpload['file'])) {
                if (!empty($dataUpload['action_type']) && $dataUpload['action_type'] == Media::ACTION_UPLOAD) {
                    $this->_handleAlbumLocationImageAfter($albumLocationMediaEntity, $dataUpload['file'], true);
                } else if(!empty($dataUpload['action_type']) && $dataUpload['action_type'] == Media::ACTION_UPDATE) {
                    $this->_handleAlbumLocationImageAfter($albumLocationMediaEntity, $dataUpload['file'], false);
                }
            }

            $this->commitTransaction();

            return $albumLocationMediaEntity->fresh(['mediaInformation', 'comments', 'albumLocation']);
        } catch (Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    private function _handleAlbumLocationImageAfter(AlbumLocationMediaModel $albumLocationMedia, UploadedFile $file, $stamp = TRUE)
    {
        $albumLocation = $albumLocationMedia->albumLocation;
        $album = $albumLocation->album;
        $folder = $album->user_id . '/' . $album->id . '/' . $albumLocation->id;
        $fileName = $this->_uploadMediaService->uploadImageAlbum($file, $folder, $stamp);
        $dataImage = [
            'image_after_path'  =>  $fileName,
            'thumbnail_path'    =>  null,
            'type'              =>  Media::TYPE_IMAGE,
            'after_size'        =>  $file->getSize(),
            'size'              =>  $albumLocationMedia->size + $file->getSize() - $albumLocationMedia->after_size
        ];
        $sizeUpdate = $file->getSize() - $albumLocationMedia->after_size;
        return $this->_updateImageAfterAlbumLocationMedias($albumLocationMedia, $dataImage, $sizeUpdate);
    }

    private function _updateImageAfterAlbumLocationMedias(AlbumLocationMediaModel $albumLocationMedia, Array $mediaData, int $sizeUpdate)
    {
        try {
            $this->beginTransaction();
            $oldAlbumLocationMediaEntity = $albumLocationMedia->replicate();
            $albumLocationMedia->update($mediaData);
            $this->_dataUsageStatisticService->updateAlbumSize($albumLocationMedia->albumLocation->album, $sizeUpdate);
            $this->_dataUsageStatisticService->updateCountDataUserUsage($albumLocationMedia->albumLocation->album->user->userUsage, $sizeUpdate);
            $this->_dataUsageStatisticService->updateCountDataCompanyUsage($albumLocationMedia->albumLocation->album->user->company->companyUsage, $sizeUpdate);
            $this->commitTransaction();
            Storage::disk(Disk::ALBUM)->delete($oldAlbumLocationMediaEntity->image_after_path);
            return $albumLocationMedia;
        } catch (\Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function swapAfterBeforeImageAlbum(int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $currentUser = app('currentUser');
        $albumLocationMediaEntity = $this->_albumLocationMediaRepository->find($albumLocationMediaId);
        if ($albumLocationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
        if ($albumLocationMediaEntity->albumLocation->id != $albumLocationId) {
            throw new UnprocessableException('messages.album_location_is_incorrect');
        }
        if ($albumLocationMediaEntity->albumLocation->album->id != $albumId) {
            throw new UnprocessableException('messages.album_id_is_incorrect');
        }

        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumLocationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if ($albumLocationMediaEntity->type != Media::TYPE_IMAGE) {
            throw new ForbiddenException('messages.feature_only_supported_image');
        }

        if (!isset($albumLocationMediaEntity->image_after_path)) {
            throw new ForbiddenException('messages.feature_only_supported_media_has_after_image');
        }

        try {
            $this->beginTransaction();
            $afterPath = $albumLocationMediaEntity->image_after_path;
            $afterSize = $albumLocationMediaEntity->after_size;
            $beforePath = $albumLocationMediaEntity->path;
            $beforeSize = $albumLocationMediaEntity->size;
            $mediaData = [
                'image_after_path'  =>  $beforePath,
                'path'              =>  $afterPath,
                'after_size'        =>  $beforeSize,
                'before_size'       =>  $afterSize,
            ];
            $albumLocationMediaEntity->update($mediaData);
            $this->commitTransaction();
            return $albumLocationMediaEntity->fresh(['mediaInformation', 'comments', 'albumLocation']);
        } catch (Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function deleteImageAfterAlbumLocationMedia(int $albumId, int $albumLocationId, int $albumLocationMediaId)
    {
        $currentUser = app('currentUser');
        $albumLocationMediaEntity = $this->_albumLocationMediaRepository
            ->with(['albumLocation.album.user.userUsage', 'albumLocation.album.user.company.companyUsage'])
            ->find($albumLocationMediaId);
        if ($albumLocationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
        if ($albumLocationMediaEntity->albumLocation->id != $albumLocationId) {
            throw new UnprocessableException('messages.album_location_is_incorrect');
        }
        if ($albumLocationMediaEntity->albumLocation->album->id != $albumId) {
            throw new UnprocessableException('messages.album_id_is_incorrect');
        }

        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumLocationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if ($albumLocationMediaEntity->type != Media::TYPE_IMAGE) {
            throw new ForbiddenException('messages.feature_only_supported_image');
        }

        try {
            $this->beginTransaction();
            $dataImage = [
                'image_after_path'  =>  null,
                'thumbnail_path'    =>  null,
                'type'              =>  Media::TYPE_IMAGE,
                'after_size'        =>  0,
                'size'              =>  $albumLocationMediaEntity->size - $albumLocationMediaEntity->after_size
            ];
            $sizeUpdate = 0 - $albumLocationMediaEntity->after_size;
            $this->_updateImageAfterAlbumLocationMedias($albumLocationMediaEntity, $dataImage, $sizeUpdate);

            $this->commitTransaction();
        } catch (Exception $exception) {
            report($exception);
            $this->rollbackTransaction();
            throw new SystemException('messages.system_error');
        }
    }

    public function uploadPdfFiles(array $files, int $userId): ?array
    {
        try {
            $list = [];
            foreach ($files as $index => $file) {
                try {
                    $list[] = $this->uploadFile($file, $userId, $index + 1);
                } catch (\Exception $e) {
                    report($e);
                }
            }
            return $list;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    private function uploadFile(UploadedFile $file, int $userId, int $index): AbstractModel
    {
        $fileName = base64_encode($index) . time() . '.' . $file->getClientOriginalExtension();
        Storage::disk(Disk::PDF)->put("{$userId}/{$fileName}", file_get_contents($file));
        return $this->_pdfFileRepository->create([
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'path' => "{$userId}/{$fileName}",
            'created_user' => app('currentUser')->id,
        ]);
    }
}
