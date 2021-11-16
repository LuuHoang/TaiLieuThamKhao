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
        *, ::after, ::before {
            box-sizing: border-box;
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
        table {
            border-collapse: collapse;
        }
        .table {
            width: 100%;
            background-color: transparent;
            font-size: 13px;
        }
        .table-bordered {
            border: 2px solid #dee2e6;
        }
        .table td{
            padding: .2rem;
            vertical-align: top;
            border-top: 2px solid #dee2e6;
            color: #ffffff;
            line-height: 1.05rem;
        }
        .table-bordered td{
            border: 2px solid #dee2e6;
        }
    </style>
</head>
<body>
@php $extendData = $data['extend_data']; @endphp
@php $location = $data['location']; @endphp
@php $images = $data['images']; @endphp
<div class="container">
    <div class="content">
        <div class="page" style="width: 734px; margin: 0 auto;">
            @foreach($images as $key => $image)
                <div class="img-wrapper" style="padding-top: 56.25%;background-image: url({{ $image['url'] }});position: relative;border: 2px solid #dee2e6; @if($key != count($images) -1) margin-bottom: 20px; @endif">
                    <div class="content-wrapper" style="position: absolute; bottom: 0; left: 0; width: 30%; background-color: green;">
                        <table class="table table-bordered" style="border-bottom: none; border-left: none;">
                            @if($extendData['album']['name'] != null)
                                <tr>
                                    <td style="border-left: none; width: 33.33%;">{{ $extendData['album']['name']->albumProperty->title }}</td>
                                    <td>{{ $extendData['album']['name']->value }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td style="border-left: none; width: 33.33%;">工事名</td>
                                    <td></td>
                                </tr>
                            @endif
                            <tr>
                                <td style="border-left: none; width: 33.33%;">工事場所</td>
                                <td>{{ $location['title'] }}</td>
                            </tr>
                            @if($extendData['album']['cttc'] != null)
                                <tr>
                                    <td style="border-left: none; width: 33.33%;">{{ $extendData['album']['cttc']->albumProperty->title }}</td>
                                    <td>{{ $extendData['album']['cttc']->value }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td style="border-left: none; width: 33.33%;">施工業者</td>
                                    <td></td>
                                </tr>
                            @endif
                            <tr>
                                <td style="border-left: none; width: 33.33%;">撮影日時</td>
                                <td>{{ (!empty($image['created_time']) && !ctype_space($image['created_time'])) ? $image['created_time'] : "--/--/----" }}</td>
                            </tr>
                            <tr>
                                <td style="border-bottom: none; border-left: none; width: 33.33%;">備考</td>
                                <td style="border-bottom: none; height: 3.15rem;">{{ $image['description'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</body>
</html>
