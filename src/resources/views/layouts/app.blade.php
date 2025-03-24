<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>もぎたてフリマ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        header {
            /* background-color: #f8fafc; */
            background-color:white;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 20px;
            color: orange;
        }

        .container {
            padding: 20px;
        }
    </style>
    <!-- 個別ページ用（後に読み込む）-->
    @stack('styles')
</head>

<body>
    <header>
        mogitate
    </header>
    <div class="container">
        @yield('content')
    </div>
</body>

</html>
