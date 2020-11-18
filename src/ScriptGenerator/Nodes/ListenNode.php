<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator\Nodes;

class ListenNode extends ScriptNode
{
    /**
     * @var string The name of the Echo event to listen to.
     */
    private string $eventName;

    /**
     * @var string The Javascript callback used as the event listener.
     */
    private string $listenerCallback;

    /**
     * ListenNode constructor.
     *
     * @param string $eventName The name of the echo event.
     * @param string $callback  A javascript event callback used as the listener.
     */
    public function __construct(string $eventName, string $callback)
    {
        $this->eventName = $eventName;
        $this->listenerCallback = $callback;
    }

    public function __toString()
    {
        return sprintf(".listen('%s', %s)", $this->eventName, $this->listenerCallback);
    }
}
