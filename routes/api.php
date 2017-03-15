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
    $api->get('/comments/{id}', 'App\Http\Controllers\CommentController@index');

    $api->group(['middleware' => 'jwt.auth'], function (\Dingo\Api\Routing\Router $api) {
        // Endpoints registered here will have the "auth" middleware applied.
        $api->get('/usersites', 'App\Http\Controllers\TentSitesController@getUserTentSites');
        $api->get('/unapproved', 'App\Http\Controllers\TentSitesController@getUnapproved');
        $api->get('/user', 'App\Http\Controllers\UserController@index');
        $api->post('/tentsites', 'App\Http\Controllers\TentSitesController@store');
        $api->put('/tentsites', 'App\Http\Controllers\TentSitesController@update');
        $api->post('/like/{id}', 'App\Http\Controllers\LikeController@handleLike');
        $api->post('/comments/{id}', 'App\Http\Controllers\CommentController@store');

    });

});


/**
 * Admin
 */
Route::group(['middleware' => 'admin'], function () {
    Route::post('admin/approve/{id}', 'TentSitesController@approve');
    Route::post('admin/deny/{id}', 'TentSitesController@deny');
});

