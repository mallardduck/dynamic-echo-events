<?php

namespace MallardDuck\DynamicEcho\Tests\Unit\Channels;

use MallardDuck\DynamicEcho\Channels\PrivateUserChannelParameters;
use MallardDuck\DynamicEcho\Tests\BaseTest;
use Illuminate\Broadcasting\PrivateChannel;

class BasicPrivateUserChannelTest extends BaseTest
{
    public function testPrivateUserChannelValues()
    {
        $channelParameters = PrivateUserChannelParameters::getInstance();
        self::assertInstanceOf(PrivateUserChannelParameters::class, $channelParameters);
        self::assertEquals(PrivateChannel::class, $channelParameters->channelType);
        self::assertEquals(
            "`App.Models.User.\${window.dynamicEcho.channelStack['d92b58a22a6162a0e23bc0a009dcdb27'].userId}`",
            $channelParameters->channelJsIdentifier
        );
    }

    public function testPrivateChannelAuthCallback()
    {
        // TODO: actually figure out how to test this.
        $channelParameters = PrivateUserChannelParameters::getInstance();
        self::assertInstanceOf(\Closure::class, $channelParameters->channelAuthCallback);
        self::assertIsCallable($channelParameters->channelAuthCallback);
    }

    public function testPrivateChannelContextBindingCallback()
    {
        // TODO: actually figure out how to test this.
        $channelParameters = PrivateUserChannelParameters::getInstance();
        self::assertInstanceOf(\Closure::class, $channelParameters->channelContextBindingCallback);
        self::assertIsCallable($channelParameters->channelContextBindingCallback);
    }

    public function testPrivateEventChannelIdentifierBindingCallback()
    {
        // TODO: actually figure out how to test this.
        $channelParameters = PrivateUserChannelParameters::getInstance();
        self::assertInstanceOf(\Closure::class, $channelParameters->eventChannelIdentifierBindingCallback);
        self::assertIsCallable($channelParameters->eventChannelIdentifierBindingCallback);
    }
}
