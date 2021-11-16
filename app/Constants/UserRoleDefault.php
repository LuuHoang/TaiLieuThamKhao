<?php

namespace App\Constants;

class UserRoleDefault
{
    const ADMIN = [
        Platform::WEB => [
            Permission::LOGIN => true,
            "module" => [
                Permission::USER_MANAGER => true,
                Permission::ALBUM_MANAGER => true,
                Permission::ALBUM_CONFIG => true,
                Permission::GUIDELINE_MANAGER => true,
                Permission::SHARE_ALBUM => true,
                Permission::COMPANY_MANAGER => true,
                Permission::PDF_CONFIG => true
            ]
        ],
        Platform::APP => [
            Permission::LOGIN => true,
            "module" => [
                Permission::SHARE_ALBUM => true
            ]
        ],
        "common" => [
            Permission::SUB_USER => false,
            Permission::ALBUM_SUB_USER_MANAGER => true,
            Permission::ALBUM_ALL_USER_MANAGER => true
        ]
    ];

    const USER = [
        Platform::WEB => [
            Permission::LOGIN => true,
            "module" => [
                Permission::USER_MANAGER => false,
                Permission::ALBUM_MANAGER => true,
                Permission::ALBUM_CONFIG => false,
                Permission::GUIDELINE_MANAGER => false,
                Permission::SHARE_ALBUM => true,
                Permission::COMPANY_MANAGER => false,
                Permission::PDF_CONFIG => false
            ]
        ],
        Platform::APP => [
            Permission::LOGIN => true,
            "module" => [
                Permission::SHARE_ALBUM => true
            ]
        ],
        "common" => [
            Permission::SUB_USER => false,
            Permission::ALBUM_SUB_USER_MANAGER => true,
            Permission::ALBUM_ALL_USER_MANAGER => false
        ]
    ];

    const SUB_USER = [
        Platform::WEB => [
            Permission::LOGIN => false,
            "module" => [
                Permission::USER_MANAGER => false,
                Permission::ALBUM_MANAGER => false,
                Permission::ALBUM_CONFIG => false,
                Permission::GUIDELINE_MANAGER => false,
                Permission::SHARE_ALBUM => false,
                Permission::COMPANY_MANAGER => false,
                Permission::PDF_CONFIG => false
            ]
        ],
        Platform::APP => [
            Permission::LOGIN => true,
            "module" => [
                Permission::SHARE_ALBUM => true
            ]
        ],
        "common" => [
            Permission::SUB_USER => true,
            Permission::ALBUM_SUB_USER_MANAGER => false,
            Permission::ALBUM_ALL_USER_MANAGER => false
        ]
    ];

    const BLANK = [
        Platform::WEB => [
            Permission::LOGIN => false,
            "module" => [
                Permission::USER_MANAGER => false,
                Permission::ALBUM_MANAGER => false,
                Permission::ALBUM_CONFIG => false,
                Permission::GUIDELINE_MANAGER => false,
                Permission::SHARE_ALBUM => false,
                Permission::COMPANY_MANAGER => false,
                Permission::PDF_CONFIG => false
            ]
        ],
        Platform::APP => [
            Permission::LOGIN => false,
            "module" => [
                Permission::SHARE_ALBUM => false
            ]
        ],
        "common" => [
            Permission::SUB_USER => false,
            Permission::ALBUM_SUB_USER_MANAGER => false,
            Permission::ALBUM_ALL_USER_MANAGER => false
        ]
    ];
}
