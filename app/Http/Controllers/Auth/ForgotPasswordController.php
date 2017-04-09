<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPasswordLink;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Database\Connection;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;
use Dingo\Api\Http\Request;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        try {
            /** @var User $user */
            $user = User::where('email', $request->only('email'))->first();
        } catch (\Exception $e) {
            return response()->json($request->only('email'), 404);
        }

        try{
            // Generate token
            $token = $this->getToken($user->getEmailForPasswordReset());
            $user->notify(new ResetPasswordLink($token));
            return $this->sendResetLinkResponse($request->only('email'));
        } catch (\Exception $e) {
            return $this->sendResetLinkFailedResponse($request, $e->getMessage().$e->getTraceAsString());
        }

    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return response()->json($response);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json($response, 400);
    }

    /**
     * Create a new token for the user.
     *
     * @param $email
     * @return string
     */
    public function getToken($email)
    {
        $token = hash_hmac('sha256', Str::random(40), $this->getHashKey());

        $this->deleteExistingToken($email);
        $this->insertToken($email, $token);

        return $token;
    }

    /**
     * @param $email
     * @param $token
     */
    protected function insertToken($email, $token)
    {
        $insert = ['email' => $email, 'token' => $token, 'created_at' => Carbon::createFromTimestamp(time())];
        // Save token i table
        \DB::table('password_resets')->insert($insert);
    }

    /**
     * Delete all existing reset tokens from the database.
     *
     * @param  $email
     * @return int
     */
    protected function deleteExistingToken($email)
    {
        return \DB::table('password_resets')->where('email', $email)->delete();
    }


    /**
     * @return mixed
     */
    private function getHashKey() {
        return env('APP_KEY');
    }
}
