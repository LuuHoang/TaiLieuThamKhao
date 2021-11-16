<?php


namespace App\Constants;


class CategorySampleContract
{
    const TRIAL = 1;

    const FIXED = 2;

    const EXTEND = 3;

    public static function get():array {
        return [
            self::TRIAL,
            self::FIXED,
            self::EXTEND,
        ];
    }
    public static function notTrial():array {
        return [
            self::FIXED,
            self::EXTEND,
        ];
    }
}
