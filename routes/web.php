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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// MTN API Routes
Route::get('/mtn/deposit', 'MTNRequestController@mTNDeposit')->name('mtn_deposit');
Route::get('/mtn/payment', 'MTNRequestController@mTNPayment')->name('mtn_payment');
Route::post('/mtn/deposit/post', 'MTNRequestController@postMTNDeposit')->name('post_mtn_deposit');
Route::post('/mtn/payment/post', 'MTNRequestController@postMTNPayment')->name('post_mtn_payment');
