<?php

namespace MallardDuck\DynamicEcho\Loader;

class LoadedEventDTO
{
    public string $eventName;
    public string $fullEventName;
    public string $channel;
    public string $jsChannelIdentifier;
    public string $jsCallback;

    /**
     * @param string $eventName
     * @param string $channel
     * @param string $jsChannelIdentifier
     * @param string $jsCallback
     *
     * @return LoadedEventDTO
     */
    public static function new(string $eventName, string $fullEventName, string $channel, string $jsChannelIdentifier, string $jsCallback): LoadedEventDTO
    {
        return new self($eventName, $fullEventName, $channel, $jsChannelIdentifier, $jsCallback);
    }

    /**
     * LoadedEventDTO constructor.
     *
     * @param string $eventName
     * @param string $channel
     * @param string $jsChannelIdentifier
     * @param string $jsCallback
     */
    public function __construct(string $eventName, string $fullEventName, string $channel, string $jsChannelIdentifier, string $jsCallback)
    {
        $this->eventName = $eventName;
        $this->fullEventName = $fullEventName;
        $this->channel = $channel;
        $this->jsChannelIdentifier = $jsChannelIdentifier;
        $this->jsCallback = $jsCallback;
    }
}
