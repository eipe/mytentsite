<?php

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
    // #Authentication routes
    // Login with registered profile
    $api->post('/login', 'App\Http\Controllers\Auth\AuthenticateController@authenticate');
    // Register new profile
    $api->post('/register', 'App\Http\Controllers\Auth\RegisterController@register');
    // Forgot password
    $api->post('/password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail');
    // Reset password
    $api->post('/password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');

    // #Tent site routes
    // Fetch tent sites
    $api->get('/tentsites/{lat?}/{lng?}/{rad?}', 'App\Http\Controllers\TentSitesController@index');
    // Fetch tent site comments
    $api->get('/comments/{id}', 'App\Http\Controllers\CommentController@index');

    $api->group(['middleware' => 'jwt.auth'], function (\Dingo\Api\Routing\Router $api) {
        // Endpoints registered here will have the "auth" middleware applied.

        // #User routes
        // Fetch user contributed tent sites
        $api->get('/usersites', 'App\Http\Controllers\TentSitesController@getUserTentSites');
        // Fetch user profile data
        $api->get('/user', 'App\Http\Controllers\UserController@index');

        // #Tent site routes
        // Upload tent site
        $api->post('/tentsites', 'App\Http\Controllers\TentSitesController@store');
        // Update tent site
        $api->put('/tentsites', 'App\Http\Controllers\TentSitesController@update');
        // Handle like/unlike tent site
        $api->post('/like/{id}', 'App\Http\Controllers\LikeController@handleLike');
        // Add comment on tent site
        $api->post('/comments/{id}', 'App\Http\Controllers\CommentController@store');

        // #Admin routes
        // Fetch unapproved tent sites
        $api->get('/unapproved', 'App\Http\Controllers\TentSitesController@getUnapproved');
        // Approve tent site
        $api->post('/admin/approve/{id}', 'App\Http\Controllers\TentSitesController@approve');
        // Deny tent site
        $api->post('/admin/deny/{id}', 'App\Http\Controllers\TentSitesController@deny');
    });
});