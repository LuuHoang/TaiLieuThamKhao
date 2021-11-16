<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'Noto Sans Regular';
            src: url('{{ public_path('fonts/NotoSansRegular.otf') }}');
        }
        html, body {
            font-family: 'Noto Sans Regular', Helvetica, Arial, sans-serif;
        }
        body {
            padding: 0;
            margin: 0;
        }

        .page {
            page-break-after: always;
            page-break-inside: avoid;
        }

        .page-break {
            page-break-after: always;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            display: inline-block;
        }

        h1 {
            font-size: 40px;
        }
        h2 {
            font-size: 32px;
        }
        h3 {
            font-size: 28px;
        }
        h4 {
            font-size: 22px;
        }
        h5  {
            font-size: 18px;
        }
        h6  {
            font-size: 16px;
        }
        .container {
            width: calc(100% - 60px);
            padding: 0 30px;
        }
        .content {
            width: 100%;
            height: 100%;
            position: relative;
        }
        .img-wrapper {
            width: 100%;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
    </style>
</head>
<body>
@php
    $album = $data['album'];
    $user = $data['user'];
    $company = $data['company'];
@endphp
<div class="container">
    <div class="content">
        <div style="display: table; width: 100%;">
            <h5 style="max-width: calc(100% - 100px);text-align: left;display: table-cell;vertical-align: middle;">{{ $user['full_name'] }} - {{ $user['email'] }}</h5>
            <div style="width: 20%;min-width: 100px;vertical-align: middle;display: table-cell;">
                <div class="img-wrapper" style="padding-top: 100% ;min-width: 150px; background-image: url({{ $company['logo_url'] }});"></div>
            </div>
        </div>
        <div style="margin-top: 50px; padding: 0 50px">
            <h1 style="width: 100%;">Album Type: {{ $album['type'] }}</h1>
        </div>
        <div style="width: 75%; margin: 50px auto 0;">
            <div class="img-wrapper" style="padding-top: 80%; background-image: url({{ $album['image_url'] }});"></div>
            <div style="margin-top: 50px;">
                @foreach($album['information'] as $albumInformation)
                    <p style="margin: 0 0 5px 0; font-size: 18px;"><b>{{ $albumInformation['title'] }}:</b> {{ $albumInformation['value'] }}</p>
                @endforeach
            </div>
        </div>
    </div>
</div>
</body>
</html>
