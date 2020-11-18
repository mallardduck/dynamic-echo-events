<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator\Nodes;

class RootContextNode extends ContextNode
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data ?? [];
    }

    public function __toString()
    {
        try {
            $data = json_encode($this->data, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return '{}';
        }

        return $data;
    }
}
