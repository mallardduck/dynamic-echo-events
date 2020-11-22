<?php

namespace MallardDuck\DynamicEcho;

use MallardDuck\DynamicEcho\Loader\LoadedEventDTO;
use MallardDuck\DynamicEcho\Collections\{
    ChannelEventCollection,
    ChannelAwareEventCollection
};

class ChannelManager
{
    /**
     * @var ChannelAwareEventCollection
     */
    private ChannelAwareEventCollection $channelCollection;

    public function __construct()
    {
        $this->channelCollection = new ChannelAwareEventCollection();
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

    public function registeredChannelNames()
    {
        return $this->channelCollection->keys();
    }

    public function registeredChannelInfo()
    {
        /**
         * @var ChannelEventCollection $channelGroup
         */
        return $this->channelCollection->map(static function ($channelGroup, $key) {
            $first = true;
            $channelInfo = [
                'authName' => $key,
                'type' => null,
                'authCallback' => null,
            ];
            /**
             * @var LoadedEventDTO $val
             */
            $events = $channelGroup->map(static function ($val) use (&$first, &$channelInfo) {
                if ($first) {
                    $first = false;
                    $channelInfo['type'] = $val->getParameter('channelType');
                    $channelInfo['authCallback'] = $val->getParameter('channelAuthCallback');
                }
                return $val->fullEventName;
            })->toArray();
            $channelInfo['events'] = $events;

            return $channelInfo;
        });
    }
}
