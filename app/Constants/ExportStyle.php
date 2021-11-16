<?php


namespace App\Constants;


class ExportStyle
{
    /**
     * Export style
     *
     * @var int
     */
    const STYLE_A = 1;
    const STYLE_B = 2;
    const STYLE_C = 3;

    const ORIENTATION = [
        'landscape' => 'landscape',
        'portrait'  => 'portrait',
    ];

    const PAPER_SIZE = [
        'a3' => 'a3',
        'a4' => 'a4',
    ];

    /**
     * Mapping data array
     *
     * @var array
     */
    const MAPPING = [
        self::STYLE_A => [
            'cover_page'    => 'style_a_cover_page',
            'content_page'  => 'style_a_content_page'
        ],
        self::STYLE_B => [
            'cover_page'    => 'style_b_cover_page',
            'content_page'  => 'style_b_content_page'
        ],
        self::STYLE_C => [
            'cover_page'    => 'style_c_cover_page',
            'content_page'  => 'style_c_content_page'
        ],
    ];

    /**
     * Mapping data config
     */
    const CONFIG = [
        self::STYLE_A => [
            'orientation'       => self::ORIENTATION['portrait'],
            'paper_size'        => self::PAPER_SIZE['a4'],
            'cover'             => true,
            'page_number'       => ['footer-center','Page [current_page]/[total_page]'],
            'merge_orientation' => 'P',
            'limit'             => 6,
        ],
        self::STYLE_B => [
            'orientation'       => self::ORIENTATION['portrait'],
            'paper_size'        => self::PAPER_SIZE['a4'],
            'cover'             => true,
            'page_number'       => ['footer-center','Page [current_page]'],
            'merge_orientation' => 'P',
            'limit'             => 6,
        ],
        self::STYLE_C => [
            'orientation'       => self::ORIENTATION['portrait'],
            'paper_size'        => self::PAPER_SIZE['a4'],
            'cover'             => true,
            'page_number'       => ['footer-center','Page [current_page]'],
            'merge_orientation' => 'P',
            'limit'             => 3,
            'extend_data'       => [
                'album'        => [
                    'name'      => '工事名',
                    'cttc'      => '施工業者'
                ]
            ]
        ]
    ];
}
