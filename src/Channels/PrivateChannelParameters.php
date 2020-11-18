<?php

namespace MallardDuck\DynamicEcho\Channels;

use Illuminate\Http\Request;
use MallardDuck\DynamicEcho\Contracts\HasDynamicChannelFormula;

/**
 * Class PrivateChannelParameters
 *
 * @mixin AbstractChannelParameters
 */
final class PrivateChannelParameters extends AbstractChannelParameters
{
    public function __construct()
    {
        $this->channelType = \Illuminate\Broadcasting\PrivateChannel::class;
        $this->channelAuthName = 'App.Models.User.{userId}';
        $this->channelAuthCallback = static function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        };
        $this->channelContextBindingCallback = static function (Request $request) {
            return [
                'userId' => $request->user()->id
            ];
        };
        $this->eventChannelIdentifierBindingCallback = static function (HasDynamicChannelFormula $event) {
            $self = $event;
            return [
                'userId' => $self->userId,
            ];
        };
        parent::__construct();
    }
}
