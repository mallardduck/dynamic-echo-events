<?php

namespace MallardDuck\DynamicEcho;

class DynamicEchoBladeDirectives
{
    public static function dynamicEchoScripts(): string
    {
        return '{!! \DynamicEcho::scripts() !!}';
    }
}
