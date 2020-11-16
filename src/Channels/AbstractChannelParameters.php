<?php

namespace MallardDuck\DynamicEcho\Channels;

abstract class AbstractChannelParameters
{
    public string $channelType;

    public ?array $channelBindingOptions;

    public string $channelIdentifierFormula;

    public string $channelJsIdentifier;

    /**
     * AbstractChannelParameters constructor.
     *
     * @note Must be called by implementing class.
     */
    public function __construct()
    {
        $this->channelJsIdentifier = $this->getJSChannelIdentifier();
    }

    public function getJSChannelIdentifier(): string
    {
        return str_replace('{', '${', $this->channelIdentifierFormula);
    }

    /**
     * @var callable
     */
    public $channelIdentifierBindingCallback;

    /**
     * @var callable
     */
    public $channelAuthCallback;
}
