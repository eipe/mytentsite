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

Route::get('auth/sign_out', 'Auth\SocialController@signOut');
Route::get('auth/{provider}', 'Auth\SocialController@redirectToProvider');
Route::get('auth/handle/{provider}', 'Auth\SocialController@handleProviderCallback');
Auth::routes();

/**
 * Admin
 */
Route::group(['middleware' => 'admin'], function () {
    Route::get('admin', 'AdminController@index');
    Route::get('admin/approve/{id}', 'TentSitesController@approve');
    Route::get('admin/deny/{id}', 'TentSitesController@deny');
});




/**
 * Errors
 */
Route::get('403', function () {
    return view('errors.403');
});


