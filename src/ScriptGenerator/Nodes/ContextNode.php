<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator\Nodes;

use BadMethodCallException;
use JsonSerializable;

class ContextNode extends BaseNode implements JsonSerializable
{
    public function jsonSerialize()
    {
        throw new BadMethodCallException(
            sprintf(
                "Method [%s] must be implemented by concrete class [%s]",
                __FUNCTION__,
                static::class
            )
        );
    }
}
