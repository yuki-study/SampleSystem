<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tasks', 'TaskController@index');
Route::get('/tax', 'TaxController@index');
Route::get('/tax/{targetYear}/{patternFlg}', 'TaxController@show');
Route::post('/tax', 'TaxController@store');


Route::get('/crypto', 'CryptoController@getAllData');
