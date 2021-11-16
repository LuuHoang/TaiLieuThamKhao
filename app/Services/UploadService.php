<?php

namespace App\Services;

use App\Constants\Disk;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Models\AlbumModel;
use App\Repositories\Repository;
use App\Supports\Facades\Image\ImageUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    protected $_albumRepository;

    protected $_commonService;

    public function __construct(AlbumModel $albumModel, CommonService $commonService)
    {
        $this->_albumRepository = new Repository($albumModel);
        $this->_commonService = $commonService;
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

    public function updateAlbumAvatar(UploadedFile $fileImage, int $albumId)
    {
        $currentUser = app('currentUser');
        $albumEntity = $this->_albumRepository->find($albumId);
        if ($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }
        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumEntity)) {
            throw new ForbiddenException('messages.not_have_permission');
        }
        $fileName = $currentUser->id . '-' . $this->_getImageName($fileImage);
        $uploadStatus = Storage::disk(Disk::IMAGE)->put($fileName, file_get_contents($fileImage));
        if (!$uploadStatus) {
            throw new SystemException('messages.system_error');
        }
        Storage::disk(Disk::IMAGE)->delete($albumEntity->image_path);
        ImageUtil::addStampToImage(Storage::disk(Disk::IMAGE)->get($fileName));
        $albumEntity->update([
            'image_path'    =>  $fileName
        ]);
        $albumEntity = $albumEntity->fresh();
        return [
            'path'  =>  $albumEntity->image_path,
            'url'   =>  Storage::disk(Disk::IMAGE)->url($albumEntity->image_path),
            'updated_at' => $albumEntity->updated_at
        ];
    }

    protected function _getImageName($image) {
        $extension = str_replace('image/', '', $image->getMimeType());
        return Carbon::now(env('TIMEZONE'))->format('Y-m-d_H-i-s-u') . '.' . $extension;
    }

    public function uploadAvatar(UploadedFile $image, $userId)
    {
        $fileName = $userId . '-' . $this->_getImageName($image);
        $uploadStatus = Storage::disk(Disk::USER)->put($fileName, file_get_contents($image));
        if (!$uploadStatus) {
            throw new SystemException('messages.system_error');
        }
        return $fileName;
    }
}
