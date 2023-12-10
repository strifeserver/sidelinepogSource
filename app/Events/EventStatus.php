<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $status;
    public $fightId;
    public $fightNumber;
    public $eventId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($status,$fightId,$fightNumber,$eventId)
    {
        $this->status = $status;
        $this->fightId = $fightId;
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
        return new Channel('event-status-'.$this->eventId);
    }

    public function broadcastAs()
    {
        return 'event-status-'.$this->eventId;
    }
}
