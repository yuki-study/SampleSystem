<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 初回アクセス時のみLaravel側でリクエストを受けてページを表示し、それ以降はフロント側のVue Routerによってルーティングが行われます
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
