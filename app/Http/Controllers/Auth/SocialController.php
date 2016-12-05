<?php

namespace App\Http\Controllers\Auth;

use App\Models\SocialLogin;
use App\Models\User;
use App\Notifications\NewUser;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;

class SocialController
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @param string $provider
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        if(!Config::get('services.' . $provider))
        {
            die('Provider ' . $provider . ' is not supported');
        }
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param string $provider
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        // Check that provider exists as a service
        if(!Config::get('services.' . $provider))
        {
            die('Provider ' . $provider . ' is not supported');
        }

        if(Input::get('denied') != '')
        {
            die('You did not allow us to connect with your social profile on ' . $provider);
        }

        $user = Socialite::driver($provider)->stateless()->user();

        $socialUser = null;

        //Check is this email present
        $userCheck = User::where('email', '=', $user->email)->first();

        if(!empty($userCheck))
        {
            $socialUser = $userCheck;
        }
        else
        {
            $sameSocialId = SocialLogin::where('social_id', '=', $user->id)
                ->where('provider', '=', $provider)
                ->first();

            if(!empty($sameSocialId))
            {
                $socialUser = $sameSocialId->user;
            }
            else
            {
                // There is no combination of this social id and provider, so we create new one
                $newSocialUser = new User;
                $newSocialUser->email = $user->email;
                $newSocialUser->name = $user->name;
                $newSocialUser->password = bcrypt(str_random(16));
                $newSocialUser->remember_token = str_random(64);
                $newSocialUser->api_token = str_random(60);
                $newSocialUser->save();

                $socialData = new SocialLogin;
                $socialData->social_id = $user->id;
                $socialData->provider= $provider;
                $newSocialUser->socialLogin()->save($socialData);
                $socialUser = $newSocialUser;

                // Notify slack channel, should probably change to new user event and move this to the event handler
                Notification::send(User::find($socialUser->getAttribute('id')), new NewUser($socialUser));
            }
        }

        Auth::login($socialUser, true);
        return Redirect::to('/');
    }


    public function signOut()
    {
        Auth::logout();
        return Redirect::to('/');
    }

}