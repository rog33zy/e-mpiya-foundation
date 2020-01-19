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

// MTN MoMo Middleware routes
Route::get('/momo/collection', 'MomoMiddlewareController@collection')->name('momo_middleware_collection');
Route::get('/momo/disbursement', 'MomoMiddlewareController@disbursement')->name('momo_middleware_disbusement');
