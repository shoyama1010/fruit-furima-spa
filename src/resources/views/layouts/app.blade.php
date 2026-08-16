<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fruit-フリマ</title>


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

        /* .container {
            padding: 20px;
        } */

        /* 横幅切り替え用 */
        .wide-page .container {
            max-width: 1500px;
        }

        .narrow-page .container {
            max-width: 1000px;
        }
    </style>
    
    <!-- 個別ページ用（後に読み込む）-->
    @stack('styles')
</head>

<!-- 共通レイアウト -->
<body class="@yield('body-class')">
    <header>
        <!-- mogitate -->
         Kudamono
    </header>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
