<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JumpFight implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $fightNumber;
    public $eventId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($fightNumber,$eventId)
    {
        $this->fightNumber = $fightNumber;
        $this->eventId = $eventId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('jump-number-'.$this->eventId);
    }

    public function broadcastAs()
    {
        return 'jump-number-'.$this->eventId;
    }
}
