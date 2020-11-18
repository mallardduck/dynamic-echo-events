<?php

namespace MallardDuck\DynamicEcho;

use MallardDuck\DynamicEcho\Loader\LoadedEventDTO;
use MallardDuck\DynamicEcho\Loader\ChannelAwareEventCollection;

class ChannelManager
{
    // TODO: Write Channel Manager logic that will:
    // - Track channels registered by events,
    // - Track bindings needed for channel names,
    // - Help render those bindings over to the view, and
    // - work as a queue for the loader to push data too.

    /**
     * @var ChannelAwareEventCollection
     */
    private ChannelAwareEventCollection $usedChannels;

    public function __construct()
    {
        // TODO: Make this collection unique to this use-case; i.e. require that it conform to a specific data shape.
        $this->usedChannels = new ChannelAwareEventCollection();
    }

    public function registeredChannels(): array
    {
        // TODO: actually do something that gets the list of channels
        return $this->usedChannels->toArray();
    }

    public function pushEventDto(LoadedEventDTO $newItem)
    {
        $this->usedChannels->push($newItem);
    }
}
