<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator\Nodes;

class ChannelContextNode extends ContextNode
{
    public string $channelJsVarKey;
    public array $channelContext;

    public function __construct(string $channelJsVarKey, array $channelContext)
    {
        $this->channelJsVarKey = $channelJsVarKey;
        $this->channelContext = $channelContext;
    }

    public function __toString()
    {

        $res = "";
        $count = count($this->channelContext);
        foreach ($this->channelContext as $key => $value) {
            --$count;
            $res .= sprintf("%s: %s", $key, $value);
            if ($count >= 1) {
                $res .= ",\r\n";
            }
        }
        return sprintf("%s: {%s}", $this->channelJsVarKey, $res);
    }

    public function jsonSerialize()
    {
        return json_encode([$this->channelJsVarKey => $this->channelContext]);
    }

}
