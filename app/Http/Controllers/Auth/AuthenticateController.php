<?php
/**
 * Created by PhpStorm.
 * User: petter
 * Date: 28/02/2017
 * Time: 21:18
 */

namespace App\Http\Controllers\Auth;

use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Middleware\RefreshToken;

class AuthenticateController extends \App\Http\Controllers\Controller
{
    /**
     * Authenticate user based on credentials
     * @param Request $request
     * @return mixed
     */
    public function authenticate(Request $request)
    {

        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = \JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // all good so return the token
        return response()->json(compact('token'));
    }

    /**
     * Get the autenticated user
     *
     * @return mixed
     */
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = \JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    /**
     * Authenticate based on user, intended used for third-party auth as facebook
     * @param $user
     * @return mixed
     */
    public static function authFromUser($user) {
            try {
                // attempt to verify the credentials and create a token for the user
                if (! $token = \JWTAuth::fromUser($user)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            // all good so return the token
            return response()->json(compact('token'));
    }


    /**
     * Logout action
     */
    public function logout() {
        try {
            \JWTAuth::invalidate(\JWTAuth::getToken());
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired']);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid']);
        } catch (JWTException $e) {
            return response()->json(['token_absent']);
        } catch (\Exception $e) {
            return response()->json([('token_invalid')]);
        }
        return response()->json([('token_invalid')]);
    }

    /**
     * Refresh token
     *
     * @return mixed
     */
    public function refresh() {
        try {
            $token =  \JWTAuth::refresh();
        } catch (TokenExpiredException $e) {
            return response()->json( 'token_expired', $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json('token_invalid', $e->getStatusCode());
        }
        return response()->json(compact('token'));
    }

}