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

$api = app('Dingo\Api\Routing\Router');
$api->version(['v1'], function(\Dingo\Api\Routing\Router $api) {

    $api->post('/login', 'App\Http\Controllers\Auth\AuthenticateController@authenticate');
    $api->get('/tentsites/{lat?}/{lng?}/{rad?}', 'App\Http\Controllers\TentSitesController@index');
    $api->get('/tentsites/{lat?}/{lng?}/{rad?}', 'App\Http\Controllers\TentSitesController@index');
   // $api->get('usersites/', 'App\Http\Controllers\TentSitesController@getUserTentSites')->middelware('api.auth');
});

// Tent sites
Route::resource('/tentsites', 'TentSitesController', ['except' => [
    'index', 'store', 'update','destroy'
]]);
Route::get('/tentsites/{lat?}/{lng?}/{rad?}', 'TentSitesController@index');
Route::get('/usersites', 'TentSitesController@getUserTentSites')->middleware('auth:api');
Route::get('/unapproved', 'TentSitesController@getUnapproved')->middleware('auth:api');
Route::post('/tentsites', 'TentSitesController@store')->middleware('auth:api');
Route::put('/tentsites', 'TentSitesController@update')->middleware('auth:api');

// Likes
Route::post('/like/{id}', 'LikeController@handleLike')->middleware('auth:api');

// Comments
Route::get('/comments/{id}', 'CommentController@index');
Route::post('/comments/{id}', 'CommentController@store')->middleware('auth:api');

// User
Route::get('/user', 'UserController@index')->middleware('auth:api');


/**
 * Admin
 */
Route::group(['middleware' => 'admin'], function () {
    Route::post('admin/approve/{id}', 'TentSitesController@approve');
    Route::post('admin/deny/{id}', 'TentSitesController@deny');
});

