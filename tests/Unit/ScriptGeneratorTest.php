<?php

namespace MallardDuck\DynamicEcho\Tests\Unit;

use MallardDuck\DynamicEcho\ScriptGenerator;

class ScriptGeneratorTest extends \MallardDuck\DynamicEcho\Tests\BaseTest
{
    public function testInstanceIsCorrectClass()
    {
        self::assertInstanceOf(ScriptGenerator::class, $this->app->make(ScriptGenerator::class));
    }
}
