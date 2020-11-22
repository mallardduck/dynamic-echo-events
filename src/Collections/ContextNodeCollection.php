<?php

namespace MallardDuck\DynamicEcho\Collections;

use Illuminate\Support\Collection;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ContextNode;

class ContextNodeCollection extends Collection
{

    /**
     * Push one or more items onto the end of the collection.
     *
     * @param  ContextNode|ContextNode[]  $values [optional]
     * @return $this
     */
    public function push(...$values)
    {
        foreach ($values as $value) {
            $this->items[] = $value;
        }

        return $this;
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {

        $res = $this->mapWithKeys(static function ($val, $key) {
            if (isset($val->channelJsVarKey)) {
                return [
                    $val->channelJsVarKey => $val->channelContext
                ];
            }
            return [$key => $val];
        });

        $contextClass = new \stdClass();
        $contextClass->active = false;
        $contextClass->channelStack = $res->jsonSerialize();

        return json_encode($contextClass, $options);
    }
}
