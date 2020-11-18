<?php

namespace MallardDuck\DynamicEcho\Channels;

abstract class AbstractChannelParameters
{
    public string $channelType;

    public ?array $channelBindingOptions;

    public string $channelIdentifierFormula;

    /**
     * @var callable
     */
    public $channelIdentifierBindingCallback;

    /**
     * @var callable
     */
    public $channelAuthCallback;

    /**
     * @var null|string
     */
    public ?string $channelJsEventCallback;

    public function getJsEventCallback(): string
    {
        return $this->channelJsEventCallback ?? '';
    }
}
