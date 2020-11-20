<?php

namespace MallardDuck\DynamicEcho\Collections;

use Closure;
use Illuminate\Support\Collection;
use MallardDuck\DynamicEcho\Channels\AbstractChannelParameters;
use MallardDuck\DynamicEcho\Loader\LoadedEventDTO;

/**
 * @example This is an example shape of how this collection should store data.
 *
 * {
 *     channelIdentifier: "App.Models.User.{userId}",
 *     channelAuthCallback: someCallbackMethod,
 *     items: [
 *         "ToastEvent" => MallardDuck\DynamicEcho\Loader\LoadedEventDTO
 *     ]
 * }
 */
class ChannelEventCollection extends Collection
{
    /**
     * The items contained in the collection.
     *
     * @var LoadedEventDTO[]
     */
    protected $items = [];

    /**
     * The channel route identifier/formula.
     *
     * @var string
     * @example "App.Models.User.{userId}"
     */
    private string $channelAuthName;

    /**
     * An md5 of the $channelAuthName - used for setting JS context vars.
     * @var string|null
     */
    private $channelJsVarKey;

    private string $channelJsIdentifier;

    private AbstractChannelParameters $channelParameters;

    public static function new(
        string $channelIdentifier,
        AbstractChannelParameters $channelParameters
    ): self {
        $collection = new self();
        $collection
            ->setChannelIdentifier($channelIdentifier)
            ->setChannelParameters($channelParameters);

        return $collection;
    }

    /**
     * @param string $channelIdentifier
     *
     * @return $this
     */
    private function setChannelIdentifier(string $channelIdentifier): self
    {
        $this->channelAuthName = $channelIdentifier;
        $this->channelJsVarKey = md5($channelIdentifier);
        return $this;
    }

    /**
     * @param AbstractChannelParameters $channelParameters
     *
     * @return $this
     */
    private function setChannelParameters(AbstractChannelParameters $channelParameters): self
    {
        $this->channelParameters = $channelParameters;

        return $this;
    }

    /**
     * Get the channel identifier for this ChannelEventCollection.
     *
     * @return string
     */
    public function getChannelAuthName(): string
    {
        return $this->channelAuthName;
    }

    public function getChannelJsVarKey(): string
    {
        return $this->channelJsVarKey;
    }

    /**
     * Get the channels authentication callback to verify user access.
     *
     * @return callable
     */
    public function getChannelAuthCallback(): callable
    {
        return $this->channelParameters->channelAuthCallback;
    }

    /**
     * Get the channels authentication callback to verify user access.
     *
     * @return null|array
     */
    public function getChannelAuthOptions(): ?array
    {
        if (0 !== count($this->channelParameters->channelAuthOptions)) {
            return $this->channelParameters->channelAuthOptions;
        }
        return null;
    }

    /**
     * @return callable|Closure|string
     */
    public function getChannelContextBindingCallback()
    {
        return $this->channelParameters->channelContextBindingCallback;
    }

    /**
     * Get the string that identifies the echo channel in Javascript.
     *
     * This will return a JS "Template literal" string.
     * It should allow for the specific user's channel's to be dynamically resolved.
     *
     * @return string
     * @example '`App.Models.User.${window.dynamicEchoOld.userID}`'
     */
    public function getChannelJsIdentifier(): string
    {
        return $this->channelParameters->channelJsIdentifier;
    }
}
