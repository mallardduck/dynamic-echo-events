<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator;

use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ListenNode;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\PrivateChannelNode;
use MallardDuck\DynamicEcho\ScriptGenerator\NOdes\RootContextNode;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\RootEchoNode;

class ScriptNodeBuilder
{
    public static function getRootContextNode(): RootContextNode
    {
        return new RootContextNode();
    }

    /**
     * @return RootEchoNode
     */
    public static function getRootEchoNode(): RootEchoNode
    {
        return new RootEchoNode();
    }

    /**
     * Builds a private Echo channel.
     *
     * @param string $channelIdentifier The name of the echo event channel.
     *
     * @return PrivateChannelNode
     */
    public static function getPrivateChannelNode(string $channelIdentifier): PrivateChannelNode
    {
        return new PrivateChannelNode($channelIdentifier);
    }

    /**
     * Builds a private Echo channel.
     *
     * @param string $eventName The name of the echo event.
     *
     * @param string $callback  A javascript event callback used as the listener.
     *
     * @return ListenNode
     */
    public static function getListenNode(string $eventName, string $callback): ListenNode
    {
        return new ListenNode($eventName, $callback);
    }
}
