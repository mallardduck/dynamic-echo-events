<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Support\Collection;

/**
 * @example This is an example shape of how this collection should store data.
 *
 * items: [
 *   "App.Models.User.{userId}" => ChannelEventCollection {
 *     channel: "App.Models.User.{userId}",
 *     channelAuthCallback: someCallbackMethod,
 *     items: [
 *       "ToastEvent" => MallardDuck\DynamicEcho\Loader\LoadedEventDTO
 *     ]
 *   },
 *   "App.Models.Game.{gameId}.User.{userId}" => ChannelEventCollection {
 *     channel: "App.Models.Game.{gameId}.User.{userId}",
 *     channelAuthCallback: someCallbackMethod,
 *     items: [
 *       "SomeEvent" => MallardDuck\DynamicEcho\Loader\LoadedEventDTO,
 *       "SomeEvent2" => MallardDuck\DynamicEcho\Loader\LoadedEventDTO,
 *     ]
 *   }
 * ]
 */
class ChannelAwareEventCollection extends Collection
{

    /**
     * The items contained in the collection.
     *
     * @var ChannelEventCollection[] $items
     */
    protected $items = [];

    /**
     * Push one or more items onto the end of the collection.
     *
     * @param  LoadedEventDTO|LoadedEventDTO[]  $values [optional]
     * @return $this
     */
    public function push(...$values)
    {
        // TODO: Update push to use ChannelEventCollection somehow.
        foreach ($values as $key => $value) {
            $identifier = $value->getParameter('channelAuthName');

            if (!$this->has($identifier)) {
                $channelEventCollection = ChannelEventCollection::new(
                    $identifier,
                    $value->getParameter('channelAuthCallback'),
                    $value->getParameter('channelAuthOptions')
                );
            } else {
                $channelEventCollection = $this->get($identifier);
            }
            $channelEventCollection = $channelEventCollection->push($value);
            $this->items[$identifier] = $channelEventCollection;
        }

        return $this;
    }

    public function pop()
    {
        throw new \BadMethodCallException(
            "This method should not be called use an alternatives; either popChannel or popChannelEvent."
        );
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @return ChannelEventCollection|null
     */
    public function popChannel(): ?ChannelEventCollection
    {
        return array_pop($this->items);
    }

    /**
     * Get and remove the last item from the collection.
     *
     * Just like normal array_pop, if the array is empty it will return null.
     *
     * @param string $channelName The name of the Channel that we're popping an event from.
     *
     * @return LoadedEventDTO|null
     */
    public function popChannelEvent(string $channelName): ?LoadedEventDTO
    {
        if (array_key_exists($channelName, $this->items)) {
            return $this->items[$channelName]->pop();
        }

        return null;
    }
}
