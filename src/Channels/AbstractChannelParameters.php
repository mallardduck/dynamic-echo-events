<?php

namespace MallardDuck\DynamicEcho\Channels;

abstract class AbstractChannelParameters
{
    public string $channelType;

    public string $channelIdentifierFormula;

    /**
     * @var callable|string
     */
    public $channelAuthCallback;

    public ?array  $channelBindingOptions;
}
