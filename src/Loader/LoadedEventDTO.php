<?php

namespace MallardDuck\DynamicEcho\Loader;

use MallardDuck\DynamicEcho\Channels\AbstractChannelParameters;

class LoadedEventDTO
{
    public string $eventName;
    public string $fullEventName;

    /**
     * @var AbstractChannelParameters
     */
    private AbstractChannelParameters $channelParameters;

    /**
     * @param string                    $eventName
     * @param string                    $fullEventName
     * @param AbstractChannelParameters $channelParameters
     *
     * @return LoadedEventDTO
     */
    public static function new(
        string $eventName,
        string $fullEventName,
        AbstractChannelParameters $channelParameters
    ): LoadedEventDTO {
        return new self($eventName, $fullEventName, $channelParameters);
    }

    /**
     * LoadedEventDTO constructor.
     *
     * @param string $eventName
     * @param string $channel
     * @param string $jsChannelIdentifier
     * @param string $jsCallback
     */
    public function __construct(string $eventName, string $fullEventName, AbstractChannelParameters $channelParameters)
    {
        $this->eventName = $eventName;
        $this->fullEventName = $fullEventName;
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
