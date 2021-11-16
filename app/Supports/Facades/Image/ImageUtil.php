<?php

namespace App\Supports\Facades\Image;

use Illuminate\Support\Facades\Facade;

class ImageUtil extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'image';
    }
}
