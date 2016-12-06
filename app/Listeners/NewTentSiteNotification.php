<?php

namespace App\Listeners;

use App\Events\NewTentSiteRegistered;
use App\Models\TentSites;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewTentSite;

class NewTentSiteNotification
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
     * @param  NewTentSiteRegistered  $event
     * @return void
     */
    public function handle(NewTentSiteRegistered $event)
    {
        $tentSite = $event->tentSite;
        Notification::send(TentSites::find($tentSite->getAttribute('id')), new NewTentSite($tentSite));

    }
}
