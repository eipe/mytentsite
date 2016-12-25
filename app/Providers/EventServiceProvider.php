<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\NewTentSiteRegistered' => [
            'App\Listeners\NewTentSiteNotification',
        ],
        'App\Events\UserRegisteredThroughSocialite' => [
            'App\Listeners\NewSocialiteUserNotification',
        ],
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\NewUserRegisteredNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
