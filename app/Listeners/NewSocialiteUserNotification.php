<?php

namespace App\Listeners;

use App\Events\UserRegisteredThroughSocialite;
use App\Models\User;
use App\Notifications\NewUser;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewSocialiteUserNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegisteredThroughSocialite $event
     * @return void
     */
    public function handle(UserRegisteredThroughSocialite $event)
    {

        $user = $event->user;
        // Notify slack channel, should probably change to new user event and move this to the event handler
        Notification::send(User::find($user->getAttribute('id')), new NewUser($user));
    }
}
