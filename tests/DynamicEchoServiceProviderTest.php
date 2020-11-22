<?php

namespace MallardDuck\DynamicEcho\Tests;

use MallardDuck\DynamicEcho\ChannelManager;
use MallardDuck\DynamicEcho\DynamicEchoService;
use MallardDuck\DynamicEcho\DynamicEchoServiceProvider;
use MallardDuck\DynamicEcho\Loader\EventContractLoader;
use MallardDuck\DynamicEcho\ScriptGenerator\ScriptGenerator;

class DynamicEchoServiceProviderTest extends BaseTest
{
    public function testServiceProviderLoaded()
    {
        self::assertArrayHasKey(DynamicEchoServiceProvider::class, $this->app->getLoadedProviders());
    }

    public function testChannelManagerType()
    {
        self::assertEquals(get_class($this->app->get("MallardDuck\DynamicEcho\ChannelManager")), ChannelManager::class);
    }

    public function testEventContractLoaderType()
    {
        self::assertEquals(
            get_class($this->app->get("MallardDuck\DynamicEcho\Loader\EventContractLoader")),
            EventContractLoader::class
        );
    }

    public function testScriptGeneratorType()
    {
        self::assertEquals(
            get_class($this->app->get("MallardDuck\DynamicEcho\ScriptGenerator\ScriptGenerator")),
            ScriptGenerator::class
        );
    }

    public function testDynamicEchoServiceType()
    {
        self::assertEquals(
            get_class($this->app->get("MallardDuck\DynamicEcho\DynamicEchoService")),
            DynamicEchoService::class
        );
    }
}
