<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Support\Collection;

/**
 * @example This is an example shape of how this collection should store data.
 *
 * [
 *   "App.Models.User.1" => [
 *     "ToastEvent" => MallardDuck\DynamicEcho\Loader\LoadedEventDTO
 *   ],
 *   "App.Game.1.User.1" => [
 *     "SomeEvent" => MallardDuck\DynamicEcho\Loader\LoadedEventDTO,
 *     "SomeEvent2" => MallardDuck\DynamicEcho\Loader\LoadedEventDTO,
 *   ]
 * ]
 */
class ChannelAwareEventCollection extends Collection
{

    /**
     * The items contained in the collection.
     *
     * @var array
     */
    protected $items = [];
    // TODO: something about making this specific to ChannelEventCollection

    /**
     * Create a new collection.
     *
     * @param  mixed  $items
     * @return void
     */
    public function __construct($items = [])
    {
        $this->items = $this->getArrayableItems($items);
    }

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
