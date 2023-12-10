<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeTeam implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $teamData;
    public $eventId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($teamData,$eventId)
    {
        $this->teamData = $teamData;
        $this->eventId = $eventId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('change-team-'.$this->eventId);
    }

    public function broadcastAs()
    {
        return 'change-team-'.$this->eventId;
    }
}
