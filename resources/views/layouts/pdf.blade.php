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
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>
