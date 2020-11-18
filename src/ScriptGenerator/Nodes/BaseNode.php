<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator\Nodes;

use BadMethodCallException;

abstract class BaseNode
{
    public function __toString()
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
