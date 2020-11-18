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
    private ChannelAwareEventCollection $channelCollection;

    public function __construct()
    {
        // TODO: Make this collection unique to this use-case; i.e. require that it conform to a specific data shape.
        $this->channelCollection = new ChannelAwareEventCollection();
    }

    public function registeredChannelNames(): array
    {
        return $this->channelCollection->keys()->toArray();
    }

    public function getChannelEventCollection(): ChannelAwareEventCollection
    {
        return $this->channelCollection;
    }

    /**
     * @param LoadedEventDTO $newItem
     *
     * @return $this
     */
    public function pushEventDto(LoadedEventDTO $newItem): self
    {
        $this->channelCollection = $this->channelCollection->push($newItem);

        return $this;
    }

    /**
     * Push multiple EventDTOs into the channel collection.
     *
     * @param LoadedEventDTO|LoadedEventDTO[] $newItems,...
     *
     * @return $this
     */
    public function pushEventDtos(...$newItems): self
    {
        $this->channelCollection = $this->channelCollection->push(...$newItems);

        return $this;
    }
}
