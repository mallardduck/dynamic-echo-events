<?php

namespace MallardDuck\DynamicEcho\Tests;

use MallardDuck\DynamicEcho\DynamicEchoServiceProvider;
use Orchestra\Testbench\Concerns\CreatesApplication;
use Orchestra\Testbench\TestCase;

abstract class BaseTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            DynamicEchoServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
