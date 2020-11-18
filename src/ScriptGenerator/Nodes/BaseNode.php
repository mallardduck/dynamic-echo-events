<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator\Nodes;

use BadMethodCallException;

abstract class BaseNode
{
    public function __toString()
    {
        throw new BadMethodCallException("Method must be implemented by base class.");
        return static::class;
    }
}
