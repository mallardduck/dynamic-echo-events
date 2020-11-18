<?php

namespace MallardDuck\DynamicEcho;

class DynamicEchoBladeDirectives
{
    public static function dynamicEchoContext(): string
    {
        return '{!! \DynamicEcho::context() !!}';
    }

    public static function dynamicEchoScripts(): string
    {
        return '{!! \DynamicEcho::scripts() !!}';
    }
}
