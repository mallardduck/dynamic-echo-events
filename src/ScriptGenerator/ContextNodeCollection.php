<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ChannelContextNode;

class ContextNodeCollection extends Collection
{
    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        $res = $this->mapWithKeys(static function ($val) {
            return [
                $val->channelJsVarKey => $val->channelContext
            ];
        });

        $contextClass = new \stdClass();
        $contextClass->active = false;
        $contextClass->channelStack = $res->jsonSerialize();

        return json_encode($contextClass, $options);
    }
}
