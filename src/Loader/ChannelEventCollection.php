<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Support\Collection;

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
     * @var callable
     * @example
     *     function ($user, $userId) {
     *         return (int) $user->id === (int) $userId;
     *     }
     */
    private $channelAuthCallback;

    /**
     * @var null|array
     */
    private ?array $channelAuthOptions = null;

    private string $channelJsIdentifier;

    public static function new(
        string $channelIdentifier,
        callable $channelAuthCallback,
        array $channelAuthOptions,
        string $channelJsIdentifier
    ): self {
        $collection = new self();
        $collection
            ->setChannelIdentifier($channelIdentifier)
            ->setChannelAuthCallback($channelAuthCallback)
            ->setChannelAuthOptions($channelAuthOptions)
            ->setChannelJsIdentifier($channelJsIdentifier);
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

    /**
     * @param callable $channelAuthCallback
     *
     * @return $this
     */
    private function setChannelAuthCallback(callable $channelAuthCallback): self
    {
        $this->channelAuthCallback = $channelAuthCallback;
        return $this;
    }

    /**
     * Get the channels authentication callback to verify user access.
     *
     * @return callable
     */
    public function getChannelAuthCallback(): callable
    {
        return $this->channelAuthCallback;
    }

    /**
     * @param array $channelAuthOptions
     *
     * @return $this
     */
    private function setChannelAuthOptions(array $channelAuthOptions): self
    {
        if (!empty($channelAuthOptions)) {
            $this->channelAuthOptions = $channelAuthOptions;
        }
        return $this;
    }

    /**
     * Get the channels authentication callback to verify user access.
     *
     * @return null|array
     */
    public function getChannelAuthOptions(): ?array
    {
        return $this->channelAuthOptions;
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
        return $this->channelJsIdentifier;
    }

    private function setChannelJsIdentifier(string $channelJsIdentifier): self
    {
        $this->channelJsIdentifier = $channelJsIdentifier;

        return $this;
    }

}
