<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator\Nodes;

class PrivateChannelNode extends ScriptNode
{
    private string $channelIdentifier;

    /**
     * ListenNode constructor.
     *
     * @param string $channelIdentifier The name of the echo event channel.
     */
    public function __construct(string $channelIdentifier)
    {
        $this->channelIdentifier = $channelIdentifier;
    }

    public function __toString()
    {
        return sprintf(".private(%s)", $this->channelIdentifier);
    }
}
