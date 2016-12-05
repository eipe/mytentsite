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

Route::resource('/tentsites', 'TentSitesController', ['except' => [
    'index', 'store', 'update','destroy'
]]);
Route::get('/tentsites/{lat?}/{lng?}/{rad?}', 'TentSitesController@index');
Route::post('/tentsites', 'TentSitesController@store')->middleware('auth:api');
Route::put('/tentsites', 'TentSitesController@update')->middleware('auth:api');


