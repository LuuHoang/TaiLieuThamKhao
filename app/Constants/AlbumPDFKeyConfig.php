<?php

namespace App\Constants;

class AlbumPDFKeyConfig
{
    const LIST_KEY_REGEX = [
        '/company\.company_name/i',
        '/company\.company_code/i',
        '/company\.address/i',
        '/company\.representative/i',
        '/company\.tax_code/i',
        '/company\.logo_url/i',
        '/album\.album_type/i',
        '/album\.user_creator/i',
        '/album\.image_url/i',
        '/album\.information.[0-9]+.title/i',
        '/album\.information.[0-9]+.value/i',
        '/location\.title/i',
        '/location\.description/i',
        '/location\.information\.[0-9]+\.title/i',
        '/location\.information\.[0-9]+\.value/i',
        '/medias\.[0-9]+\.url/i',
        '/medias\.[0-9]+\.name/i',
        '/medias\.[0-9]+\.created_time/i',
        '/medias\.[0-9]+\.description/i',
        '/medias\.[0-9]+\.information\.[0-9]+\.title/i',
        '/medias\.[0-9]+\.information\.[0-9]+\.value/i',
        '/medias_after\.[0-9]+\.url/i',
        '/medias_after\.[0-9]+\.name/i',
        '/medias_after\.[0-9]+\.created_time/i',
        '/medias_after\.[0-9]+\.description/i',
        '/medias_after\.[0-9]+\.information\.[0-9]+\.title/i',
        '/medias_after\.[0-9]+\.information\.[0-9]+\.value/i',
        '/shared\.link/i',
        '/shared\.password/i',
        '/shared\.guest\.name/i',
        '/shared\.guest\.email/i',
        '/shared\.guest\.content/i',
    ];

    const BEFORE_CONTENT =
        '@extends("layouts.pdf")' . PHP_EOL .
        '@php $data = Arr::dot($data); @endphp' . PHP_EOL .
        '@section("content")' . PHP_EOL;

    const AFTER_CONTENT = PHP_EOL . '@endsection';

    const BEFORE_PREVIEW_CONTENT =
        '@extends("layouts.pdf_preview")' . PHP_EOL .
        '@php $data = Arr::dot($data); @endphp' . PHP_EOL .
        '@section("content")' . PHP_EOL;

    const AFTER_PREVIEW_CONTENT = PHP_EOL . '@endsection';

}
