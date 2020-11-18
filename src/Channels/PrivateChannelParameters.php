<?php

namespace MallardDuck\DynamicEcho\Channels;

use MallardDuck\DynamicEcho\Contracts\HasDynamicChannelFormula;

/**
 * Class PrivateChannelParameters
 *
 * @mixin AbstractChannelParameters
 */
class PrivateChannelParameters extends AbstractChannelParameters
{
    public function __construct()
    {
        $this->channelType = \Illuminate\Broadcasting\PrivateChannel::class;
        $this->channelAuthName = 'App.Models.User.{userId}';
        $this->channelAuthCallback = static function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        };
        $this->channelIdentifierBindingCallback = static function (HasDynamicChannelFormula $event) {
            $self = $event;
            return [
                'userId' => $self->userId,
            ];
        };
        parent::__construct();
    }
}
