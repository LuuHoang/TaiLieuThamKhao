<?php

namespace App\Services;

use App\Constants\Disk;
use App\Exceptions\ForbiddenException;
use App\Exceptions\SystemException;
use App\Models\UserModel;
use App\Supports\Facades\Image\ImageUtil;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadMediaService extends AbstractService
{
    /**
     * Resize an image and keep the proportions
     * @param integer $max_width
     * @param integer $max_height
     * @return false|resource
     * @author Allison Beckwith <allison@planetargon.com>
     */
    function getImageStamp($max_width, $max_height)
    {
        $filename = getcwd() . '/images/ic_logo.png';
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
            $orientation = $exif['Orientation'];
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
            $marge_right = 10;
            $marge_bottom = 10;
            $imWidth = imagesx($im);
            $imHeight = imagesy($im);

            $stamp = $this->getImageStamp($imWidth * 0.2, $imHeight * 0.2);
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            // Copy the stamp image onto our photo using the margin offsets and the photo
            // width to calculate positioning of the stamp.
            imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, $sx, $sy);
            imagejpeg($im, $imagePath);

        } catch (\Exception $e) {
            report($e);
        }
    }

    public function uploadImage(UploadedFile $file, String $disk, $stamp = TRUE)
    {
        $fileName = $this->_getMediaName($file);
        $uploadStatus = Storage::disk($disk)->put($fileName, file_get_contents($file));
        if (!$uploadStatus) {
            throw new SystemException('messages.system_error');
        }
        if ($stamp) {
            ImageUtil::addStampToImage(Storage::disk('public')->path($disk.'/'. $fileName));
        }
        return $fileName;
    }

    public function uploadImageAlbum(UploadedFile $file, ?string $folder = null, $stamp = TRUE)
    {
        $fileName = $folder ? $folder . '/' . Disk::IMAGE . '/' . $this->_getMediaName($file) : $this->_getMediaName($file);
        $uploadStatus = Storage::disk(Disk::ALBUM)->put($fileName, file_get_contents($file));
        if (!$uploadStatus) {
            throw new SystemException('messages.system_error');
        }
        if ($stamp) {
            ImageUtil::addStampToImage(Storage::disk(Disk::ALBUM)->path($fileName));
        }
        return $fileName;
    }

    public function deleteMedia(String $mediaPath, String $disk) {
        Storage::disk($disk)->delete($mediaPath);
    }

    private function _getMediaName(UploadedFile $file)
    {
        $extension = $file->extension();
        if (app()->bound('currentUser')) {
            $user = app('currentUser');
            return $user->id . '-' . now()->format('Y-m-d_H-i-s-u') . '.' . $extension;
        } else {
            return time() . '.' . $extension;
        }
    }

    public function UploadVideo(UploadedFile $file)
    {
        try {
            $fileInfo = $this->_getMediaInfo($file);
            $fileName = $fileInfo['name'] . '.' . $fileInfo['extension'];
            $fileThumbName = $fileInfo['name'] . '_thumb.png';
            Storage::disk(Disk::VIDEO)->put($fileName, file_get_contents($file));

            // Generate thumbnail
            FFMpeg::fromDisk(Disk::VIDEO)
                ->open($fileName)
                ->getFrameFromSeconds(1)
                ->export()
                ->toDisk(Disk::THUMBNAIL)
                ->save($fileThumbName);

            ImageUtil::addStampToImage(Storage::disk('public')->path(Disk::THUMBNAIL.'/'. $fileThumbName));
            return [
                'file_name'         =>  $fileName,
                'file_thumb_name'   =>  $fileThumbName
            ];
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function UploadVideoAlbum(UploadedFile $file, ?string $folder = null)
    {
        try {
            $fileInfo = $this->_getMediaInfo($file);
            $fileName = $folder ? $folder . '/' . Disk::VIDEO . '/' . $fileInfo['name'] . '.' . $fileInfo['extension'] : $fileInfo['name'] . '.' . $fileInfo['extension'];
            $fileThumbName = $folder ? $folder . '/' . Disk::THUMBNAIL . '/' . $fileInfo['name'] . '_thumb.png' : $fileInfo['name'] . '_thumb.png';
            Storage::disk(Disk::ALBUM)->put($fileName, file_get_contents($file));

            // Generate thumbnail
            FFMpeg::fromDisk(Disk::ALBUM)
                ->open($fileName)
                ->getFrameFromSeconds(1)
                ->export()
                ->toDisk(Disk::ALBUM)
                ->save($fileThumbName);

            ImageUtil::addStampToImage(Storage::disk(Disk::ALBUM)->path($fileThumbName));
            return [
                'file_name'         =>  $fileName,
                'file_thumb_name'   =>  $fileThumbName
            ];
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    private function _getMediaInfo(UploadedFile $file)
    {
        $extension = $file->extension();
        if (app()->bound('currentUser')) {
            $user = app('currentUser');
            $name = $user->id . '-' . now()->format('Y-m-d_H-i-s-u');
        } else {
            $name = time();
        }
        $size = $file->getSize();
        return [
            'name'  =>  $name,
            'extension' =>  $extension,
            'size'      =>  $size
        ];
    }

    public function checkAllowToUploadMedia(UserModel $user, int $size)
    {
        $commonService = app(CommonService::class);
        $userCreated = null;
        if ($commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true))) {
            $userCreated = $user->userCreated;
        } else {
            $userCreated = $user;
        }
        $subUsers = $userCreated->subUsers;
        $countData = $userCreated->userUsage->count_data;
        if ($subUsers->isNotEmpty()) {
            foreach ($subUsers as $subUser) {
                $countData += $subUser->userUsage->count_data;
            }
        }
        if ($userCreated->userUsage->package_data + $userCreated->userUsage->extend_data - $countData < $size) {
            throw new ForbiddenException('messages.file_size_exceeds_the_maximum_allowed_size');
        }
    }
}
