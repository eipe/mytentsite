<?php

namespace App\Events;

use App\Models\Like;
use App\Models\TentSites;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewLikeRegistered implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $like;
    public $user;

    /**
     * Create a new event instance.
     * @param TentSites $tentSite
     * @return void
     */
    public function __construct(Like $like, $user)
    {
        $this->like = $like;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('like');
    }
}
