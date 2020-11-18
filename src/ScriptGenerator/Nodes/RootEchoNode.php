<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator\Nodes;

class RootEchoNode extends ScriptNode
{
    public function __toString()
    {
        return "Echo";
    }
}
