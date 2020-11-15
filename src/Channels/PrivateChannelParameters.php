<?php

namespace MallardDuck\DynamicEcho\Channels;

class PrivateChannelParameters extends AbstractChannelParameters
{
    public function __construct()
    {
        $this->channelType = \Illuminate\Broadcasting\PrivateChannel::class;
        $this->channelIdentifierFormula = 'App.Models.User.{userId}';
        $this->channelAuthCallback = static function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        };
    }
}
