<?php

namespace MallardDuck\DynamicEcho\Loader;

use MallardDuck\DynamicEcho\Channels\AbstractChannelParameters;

class LoadedEventDTO
{
    public string $eventName;
    public string $fullEventName;
    public string $jsEventCallback;

    /**
     * @var AbstractChannelParameters
     */
    private AbstractChannelParameters $channelParameters;

    /**
     * @param string                    $eventName
     * @param string                    $fullEventName
     * @param string                    $jsEventCallback
     * @param AbstractChannelParameters $channelParameters
     *
     * @return LoadedEventDTO
     */
    public static function new(
        string $eventName,
        string $fullEventName,
        string $jsEventCallback,
        AbstractChannelParameters $channelParameters
    ): LoadedEventDTO {
        return new self($eventName, $fullEventName, $jsEventCallback, $channelParameters);
    }

    /**
     * LoadedEventDTO constructor.
     *
     * @param string                    $eventName
     * @param string                    $fullEventName
     * @param string                    $jsEventCallback
     * @param AbstractChannelParameters $channelParameters
     */
    public function __construct(
        string $eventName,
        string $fullEventName,
        string $jsEventCallback,
        AbstractChannelParameters $channelParameters
    ) {
        $this->eventName = $eventName;
        $this->fullEventName = $fullEventName;
        $this->jsEventCallback = $jsEventCallback;
        $this->channelParameters = $channelParameters;
    }

    public function getChannelParameters(): AbstractChannelParameters
    {
        return $this->channelParameters;
    }

    public function getParameter(string $identifierKey)
    {
        return $this->channelParameters->{$identifierKey};
    }
}
