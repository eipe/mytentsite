<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\NewUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;


class NewUserRegisteredNotification
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {

        $user = $event->user;
        // Notify slack channel, should probably change to new user event and move this to the event handler
        Notification::send(User::find($user->getAttribute('id')), new NewUser($user));
    }
}
