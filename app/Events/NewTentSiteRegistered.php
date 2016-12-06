<?php

namespace App\Events;

use App\Models\TentSites;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewTentSiteRegistered
{
    use InteractsWithSockets, SerializesModels;


    public $tentSite;

    /**
     * Create a new event instance.
     * @param TentSites $tentSite
     * @return void
     */
    public function __construct(TentSites $tentSite)
    {
        $this->tentSite = $tentSite;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
