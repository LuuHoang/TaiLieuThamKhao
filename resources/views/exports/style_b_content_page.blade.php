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
    $location = $data['location'];
    $images = $data['images'];
@endphp
<div class="container">
    <div class="content">
        <div class="page">
            <div style="padding: 0 50px; margin-bottom: 50px;">
                <h2 style="width: 100%">{{ $location['title'] }}</h2>
            </div>
            @php $count = 0; @endphp
            @foreach($images as $image)
                @if($count % 2 == 0)
                    <div style="width: 400px; float: left">
                        <div class="img-wrapper" style="padding-top: 75%; background-image: url({{ $image['url'] }});"></div>
                        <p style="margin: 0 0 30px 0; font-size: 18px;"><b>説明:</b> {{ $image['description'] }}</p>
                    </div>
                @else
                    <div style="width: 400px; float: right">
                        <div class="img-wrapper" style="padding-top: 75%; background-image: url({{ $image['url'] }});"></div>
                        <p style="margin: 0 0 30px 0; font-size: 18px;"><b>説明:</b> {{ $image['description'] }}</p>
                    </div>
                    <div style="clear: both"></div>
                @endif
                @php $count = $count + 1; @endphp
            @endforeach
            <div style="clear: both"></div>
        </div>
    </div>
</div>
</body>
</html>
