<?php

namespace MallardDuck\DynamicEcho\Tests\Unit\ScriptGenerator;

use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ChannelContextNode;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ListenNode;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\PrivateChannelNode;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\RootContextNode;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\RootEchoNode;
use MallardDuck\DynamicEcho\ScriptGenerator\ScriptNodeBuilder;
use MallardDuck\DynamicEcho\Tests\BaseTest;

class ScriptNodeBuilderTest extends BaseTest
{
    private ScriptNodeBuilder $scriptNodeBuilder;

    protected function setUp(): void
    {
        $this->scriptNodeBuilder = new ScriptNodeBuilder();
        parent::setUp();
    }

    public function testInstanceIsCorrectClass()
    {
        self::assertInstanceOf(ScriptNodeBuilder::class, $this->scriptNodeBuilder);
    }

    public function testRootContextNodeInstance()
    {
        self::assertInstanceOf(
            RootContextNode::class,
            $this->scriptNodeBuilder::getRootContextNode(["message" => "Hello World, I love you."])
        );
    }

    public function testRootEchoNodeInstance()
    {
        self::assertInstanceOf(
            RootEchoNode::class,
            $this->scriptNodeBuilder::getRootEchoNode()
        );
    }

    public function testPrivateChannelNodeInstance()
    {
        self::assertInstanceOf(
            PrivateChannelNode::class,
            $this->scriptNodeBuilder::getPrivateChannelNode("private-channel")
        );
    }

    public function testListenNodeInstance()
    {
        self::assertInstanceOf(
            ListenNode::class,
            $this->scriptNodeBuilder::getListenNode("test", "function () {console.log(\"HI\")}")
        );
    }

    public function testChannelContextNodeInstance()
    {
        self::assertInstanceOf(
            ChannelContextNode::class,
            $this->scriptNodeBuilder::getChannelContextNode('window.something', [])
        );
    }
}
