<?php

namespace App\Supports\Components\Image;

use App\Constants\Disk;
use App\Constants\Media;
use App\Constants\StampType;
use Illuminate\Support\Facades\Storage;

class ImageUtil
{
    public function addStampToImage(string $imagePath)
    {
        try {
            // Load the stamp and the photo to apply the watermark to
            if(mime_content_type($imagePath) === Media::IMAGE_JPEG){
                header('Content-Type: image/jpeg');
                $im = imagecreatefromjpeg($imagePath);
                $exif = exif_read_data($imagePath);
                $orientation = $exif['Orientation'] ?? 1;
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
            }
            if(mime_content_type($imagePath) === Media::IMAGE_PNG)
            {
                header('Content-Type: image/png');
                $im = imagecreatefrompng($imagePath);
            }
            // Set the margins for the stamp and get the height/width of the stamp image
            $imWidth = imagesx($im);
            $imHeight = imagesy($im);

            $config = $config = app('currentUser')->company->config;
            if ($config->stamp_type === StampType::ICON) {
                $stamp = $this->getImageStamp(Storage::disk(Disk::COMPANY)->path($config->icon_path), $imWidth * 0.2, $imHeight * 0.2);

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

    private function getImageStamp(string $path, $max_width, $max_height)
    {
        [$orig_width, $orig_height] = getimagesize($path);

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

        $image = imagecreatefrompng($path);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0,
            $width, $height, $orig_width, $orig_height);

        return $image_p;
    }

    private function getCoordinates($type, $imageInfo, $stampInfo, $isText = false): array
    {
        $margin = 10;
        $coordinates = [
            'x' => 0,
            'y' => 0,
        ];
        switch ($type) {
            case StampType::TOPLEFT:
                $coordinates['x'] = $margin;
                $coordinates['y'] = $isText ? $margin + 24 : $margin;
                break;
            case StampType::TOPCENTER:
                $coordinates['x'] = ($imageInfo['x'] - $stampInfo['x']) / 2;
                $coordinates['y'] = $isText ? $margin + 24 : $margin;
                break;
            case StampType::TOPRIGHT:
                $coordinates['x'] = $imageInfo['x'] - $stampInfo['x'] - $margin;
                $coordinates['y'] = $isText ? $margin + 24 : $margin;
                break;
            case StampType::CENTERLEFT:
                $coordinates['x'] = $margin;
                $coordinates['y'] = ($imageInfo['y'] - $stampInfo['y']) / 2;
                break;
            case StampType::CENTERCENTER:
                $coordinates['x'] = ($imageInfo['x'] - $stampInfo['x']) / 2;
                $coordinates['y'] = ($imageInfo['y'] - $stampInfo['y']) / 2;
                break;
            case StampType::CENTERRIGHT:
                $coordinates['x'] = $imageInfo['x'] - $stampInfo['x'] - $margin;
                $coordinates['y'] = ($imageInfo['y'] - $stampInfo['y']) / 2;
                break;
            case StampType::BOTTOMLEFT:
                $coordinates['x'] = $margin;
                $coordinates['y'] = $imageInfo['y'] - $stampInfo['y'] - $margin;
                break;
            case StampType::BOTTOMCENTER:
                $coordinates['x'] = ($imageInfo['x'] - $stampInfo['x']) / 2;
                $coordinates['y'] = $imageInfo['y'] - $stampInfo['y'] - $margin;
                break;
            case StampType::BOTTOMRIGHT:
                $coordinates['x'] = $imageInfo['x'] - $stampInfo['x'] - $margin;
                $coordinates['y'] = $imageInfo['y'] - $stampInfo['y'] - $margin;
                break;
        }
        return $coordinates;
    }

    private function getTextInformation(string $text, string $fontPath): array
    {
        $im = new \Imagick();

        /* Create an ImagickDraw object */
        $draw = new \ImagickDraw();

        /* Set the font */
        $draw->setFont($fontPath);
        $properties = $im->queryFontMetrics($draw, $text);

        $type_space = imageftbbox( 24, 0, $fontPath, $text);
        $image_width = abs($type_space[4] - $type_space[0]) + 10;
        $image_height = abs($type_space[5] - $type_space[1]) + 10;
        return [
            'width' => $image_width,
            'height' => $image_height,
        ];
    }
}
