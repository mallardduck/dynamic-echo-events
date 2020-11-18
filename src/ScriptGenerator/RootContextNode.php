<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator;

class RootContextNode extends BaseNode
{
    public function __toString()
    {
        try {
            $data = json_encode([], JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return '';
        }

        return $data;
    }
}
