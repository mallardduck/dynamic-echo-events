<?php

namespace MallardDuck\DynamicEcho\Traits;

use Illuminate\Broadcasting\PrivateChannel;

trait DynamicEchoChannel
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->userId);
    }
}
