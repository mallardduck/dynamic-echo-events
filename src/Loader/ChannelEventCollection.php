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
    private string $channelIdentifier;

    /**
     * @var callable
     * @example
     *     function ($user, $id) {
     *         return (int) $user->id === (int) $id;
     *     }
     */
    private $channelAuthCallback;

    public static function new(string $channelIdentifer, callable $channelAuthCallback): self
    {
        $collection = new self();
        $collection->setChannelIdentifier($channelIdentifer)->setChannelAuthCallback($channelAuthCallback);
        return $collection;
    }

    /**
     * @param string $channelIdentifier
     *
     * @return $this
     */
    private function setChannelIdentifier(string $channelIdentifier): self
    {
        $this->channelIdentifier = $channelIdentifier;
        return $this;
    }

    /**
     * Get the channel identifier for this ChannelEventCollection.
     *
     * @return string
     */
    public function getChannelIdentifier(): string
    {
        return $this->channelIdentifier;
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

}
