<?php

namespace MallardDuck\DynamicEcho\Channels;

use MallardDuck\DynamicEcho\Contracts\HasDynamicChannelFormula;

class PrivateChannelParameters extends AbstractChannelParameters
{
    public function __construct()
    {
        $this->channelType = \Illuminate\Broadcasting\PrivateChannel::class;
        $this->channelIdentifierFormula = 'App.Models.User.{userId}';
        $this->channelIdentifierBindingCallback = static function (HasDynamicChannelFormula $event) {
            $self = $event;
            return [
                'userId' => $self->userId,
            ];
        };
        $this->channelAuthCallback = static function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        };
        parent::__construct();
    }
}
