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
     * @var ChannelEventCollection[]
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
        foreach ($values as $value) {
            $identifier = $value->getParameter('channelIdentifierFormula');
            // TODO: Use identifier to check if ChannelEventCollection with that name exists.
            // TODO: Fetch or create new ChannelEventCollection - then push event into that.
            dd(
                $identifier,
                $this->items
            );
            $this->items[$identifier][$value->eventName] = $value;
        }

        return $this;
    }

    public function pop()
    {
        throw new \BadMethodCallException("This method should not be called anymore; use the alternative popChannel or popChannelEvent methods.");
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @return mixed
     */
    public function popChannel()
    {
        return array_pop($this->items);
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function popChannelEvent(string $key)
    {
        if (array_key_exists($key, $this->items)) {
            return array_pop($this->items[$key]);
        }

        return array_pop($this->items[$channelName]);
    }
}
