<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Support\Collection;

class ChannelAwareEventCollection extends Collection
{
    /**
     * Push one or more items onto the end of the collection.
     *
     * @param  mixed  $values [optional]
     * @return $this
     */
    public function push(...$values)
    {
        foreach ($values as $value) {
            // TODO: use this method to customize how things are stored.
            $this->items[] = $value;
        }

        return $this;
    }
}
