<?php

namespace MallardDuck\DynamicEcho\Tests;

use MallardDuck\DynamicEcho\DynamicEchoServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class BaseTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            DynamicEchoServiceProvider::class
        ];
    }
}
