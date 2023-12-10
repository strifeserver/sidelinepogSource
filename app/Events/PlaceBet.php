<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlaceBet implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $betData;
    public $eventId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($betData,$eventId)
    {
        $this->betData = $betData;
        $this->eventId = $eventId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('place-bet-'.$this->eventId);
    }

    public function broadcastAs()
    {
        return 'place-bet-'.$this->eventId;
    }
}
