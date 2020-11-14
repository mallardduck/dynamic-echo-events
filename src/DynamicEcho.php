<?php

namespace MallardDuck\DynamicEcho;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MallardDuck\DynamicEcho\DynamicEchoService
 */
class DynamicEcho extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'dynamic-echo';
    }
}
