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

Route::get('auth/{provider}', 'Auth\SocialController@redirectToProvider');
Route::get('auth/handle/{provider}', 'Auth\SocialController@handleProviderCallback');