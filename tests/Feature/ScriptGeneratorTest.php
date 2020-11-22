<?php

namespace MallardDuck\DynamicEcho\Tests\Feature;

use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ChannelContextNode;
use MallardDuck\DynamicEcho\ScriptGenerator\ScriptGenerator;
use MallardDuck\DynamicEcho\ScriptGenerator\ScriptNodeBuilder;

class ScriptGeneratorTest extends \MallardDuck\DynamicEcho\Tests\BaseTest
{
    private ScriptGenerator $scriptGenerator;

    protected function setUp(): void
    {
        $this->scriptGenerator = new ScriptGenerator();
        parent::setUp();
    }

    public function testContextPush()
    {
        $scriptGenerator = $this->scriptGenerator->pushContextNode(
            ScriptNodeBuilder::getChannelContextNode('test-channel', ["test"=>'data'])
        );
        self::assertInstanceOf(ScriptGenerator::class, $scriptGenerator);
        self::assertEquals(
            '{"active":false,"channelStack":{"test-channel":{"test":"data"}}}',
            $scriptGenerator->getRootContext()
        );
    }

    public function testScriptPush()
    {
        $scriptGenerator = $this->scriptGenerator->pushScriptNode(
            ScriptNodeBuilder::getRootEchoNode()
        );
        self::assertInstanceOf(ScriptGenerator::class, $scriptGenerator);
    }
}
