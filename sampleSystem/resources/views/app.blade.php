 <!doctype html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>サンプルプログラム</title>
     <!-- CSRF Token -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <!-- Styles webpack.mix.jsでビルドしたファイルの読み込み-->
     <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
 </head>

 <body>
     <div id="app">

     </div>
     <!-- Scripts webpack.mix.jsでビルドしたファイルの読み込み-->
     <script src="{{ mix('/js/app.js') }}" defer></script>
 </body>

 </html>