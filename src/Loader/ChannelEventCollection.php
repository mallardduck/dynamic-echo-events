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
     *     function ($user, $userId) {
     *         return (int) $user->id === (int) $userId;
     *     }
     */
    private $channelAuthCallback;

    /**
     * @var null|array
     */
    private ?array $channelAuthOptions = null;

    public static function new(
        string $channelIdentifier,
        callable $channelAuthCallback,
        array $channelAuthOptions
    ): self {
        $collection = new self();
        $collection
            ->setChannelIdentifier($channelIdentifier)
            ->setChannelAuthCallback($channelAuthCallback)
            ->setChannelAuthOptions($channelAuthOptions);
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

}
