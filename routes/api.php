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
    // Logout
    $api->post('/logout', 'App\Http\Controllers\Auth\AuthenticateController@logout');
    // Register new profile
    $api->post('/register', 'App\Http\Controllers\Auth\RegisterController@register');
    // Forgot password
    $api->post('/password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail');
    // Reset password
    $api->post('/password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');
    // Refresh token
    $api->get('/refresh', 'App\Http\Controllers\Auth\AuthenticateController@refresh');
    // #Tent site routes
    // Fetch tent sites
    $api->get('/tentsites/{lat?}/{lng?}/{rad?}', 'App\Http\Controllers\TentSitesController@index');
    // Fetch tent site comments
    $api->get('/comments/{id}', 'App\Http\Controllers\CommentController@index');
    // Handle tags
    $api->get('/tags', 'App\Http\Controllers\TagsController@index');

    // Fetch tent site comments
    $api->get('/like/{id}', 'App\Http\Controllers\LikeController@getLikesByUser');

    // Statistics
    $api->get('/statistics', 'App\Http\Controllers\StatisticsController@getPublicStatistics');

    $api->group(['middleware' => ['jwt.auth', 'last_active']], function (\Dingo\Api\Routing\Router $api) {
        // Endpoints registered here will have the "auth" middleware applied.

        // #User routes

        // Fetch user contributed tent sites
        $api->get('/usersites', 'App\Http\Controllers\TentSitesController@getUserTentSites');
        // Fetch user profile data
        $api->get('/user', 'App\Http\Controllers\UserController@index');
        $api->get('/bookmarks', 'App\Http\Controllers\TentSitesController@getBookmarkedTentSites');

        // #Tent site routes
        // Upload tent site
        $api->post('/tentsites', 'App\Http\Controllers\TentSitesController@store');
        // Update tent site
        $api->put('/tentsites', 'App\Http\Controllers\TentSitesController@update');
        // Handle like/unlike tent site
        $api->post('/like/{id}', 'App\Http\Controllers\LikeController@handleLike');
        // Handle like/unlike tent site
        $api->post('/unlike/{id}', 'App\Http\Controllers\LikeController@handleLike');
        // Add comment on tent site
        $api->post('/comments/{id}', 'App\Http\Controllers\CommentController@store');
        $api->post('/comment/{id}/delete', 'App\Http\Controllers\CommentController@delete');

        $api->delete('/delete/{id}', 'App\Http\Controllers\TentSitesController@delete');
        $api->post('/restore/{id}', 'App\Http\Controllers\TentSitesController@restore');

        // #Admin routes
        $api->post('/tags', 'App\Http\Controllers\TagsController@store');
        $api->post('/tags/{id}/delete', 'App\Http\Controllers\TagsController@destroy');
        // Fetch unapproved tent sites
        $api->get('/unapproved', 'App\Http\Controllers\TentSitesController@getUnapproved');
        // Fetch all users
        $api->get('/users', 'App\Http\Controllers\UserController@getAllUsers');
        // Approve tent site
        $api->post('/admin/approve/{id}', 'App\Http\Controllers\TentSitesController@approve');
        // Deny tent site
        $api->post('/admin/deny/{id}', 'App\Http\Controllers\TentSitesController@deny');
    });
});