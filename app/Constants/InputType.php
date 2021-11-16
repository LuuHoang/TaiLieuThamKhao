<?php


namespace App\Constants;


class InputType
{
    const TEXT          = 1;
    const DATE          = 2;
    const SHORT_DATE    = 3;
    const DATE_TIME     = 4;
    const NUMBER        = 5;
    const EMAIL         = 6;
    const IMAGES        = 7;
    const VIDEOS        = 8;
    const PDFS          = 9;

    const CONFIG        =  [
        self::DATE      => [
            'format'    => [
                'en'    => 'd/m/Y',
                'ja'    => 'Y年m月d日',
                'vi'    => 'd/m/Y'
            ]
        ],
        self::SHORT_DATE      => [
            'format'    => [
                'en'    => 'd/m',
                'ja'    => 'm月d日',
                'vi'    => 'd/m'
            ]
        ],
        self::DATE_TIME => [
            'format'    => [
                'en'    => 'd/m/Y H:i',
                'ja'    => 'Y年m月d日 H:i',
                'vi'    => 'd/m/Y H:i'
            ]
        ],
        self::EMAIL  =>  [
            'format'    =>  '/^[a-z][a-z0-9_\.]{2,32}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$/i'
        ],
        self::IMAGES    =>  [
            'extension' =>  ['png', 'jpg', 'jpeg']
        ],
        self::VIDEOS    =>  [
            'extension' =>  ['mp4', 'm4v', 'm4p']
        ],
        self::PDFS      =>  [
            'extension' =>  ['pdf']
        ]
    ];
}
