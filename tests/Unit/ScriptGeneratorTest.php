<?php

namespace MallardDuck\DynamicEcho\Tests\Unit;

use MallardDuck\DynamicEcho\ScriptGenerator;
use MallardDuck\DynamicEcho\Tests\BaseTest;

class ScriptGeneratorTest extends BaseTest
{
    public function testInstanceIsCorrectClass()
    {
        self::assertInstanceOf(ScriptGenerator::class, $this->app->make(ScriptGenerator::class));
    }
}
