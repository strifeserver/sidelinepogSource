<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Bet implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $status;
    public $eventId;
    public $oddsWala;
    public $oddsMeron;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($status,$eventId,$oddsMeron,$oddsWala)
    {
        $this->status = $status;
        $this->eventId = $eventId;
        $this->oddsMeron = $oddsMeron;
        $this->oddsWala = $oddsWala;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('betting-status-'.$this->eventId);
    }

    public function broadcastAs()
    {
        return 'betting-status-'.$this->eventId;
    }
}
