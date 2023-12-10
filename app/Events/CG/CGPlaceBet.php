<?php

namespace App\Events\CG;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CGPlaceBet implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $betData;
    public $userId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($betData,$userId)
    {
        $this->betData = $betData;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("cg-bet.{$this->userId}");
    }

    public function broadcastAs(){
        return "cg.bet";
    }
}
