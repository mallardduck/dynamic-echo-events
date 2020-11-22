<?php

namespace MallardDuck\DynamicEcho\Tests\Feature;

use MallardDuck\DynamicEcho\ScriptGenerator\ScriptNodeBuilder;
use MallardDuck\DynamicEcho\Tests\BaseTest;

class GeneratorNodesTest extends BaseTest
{
    private ScriptNodeBuilder $scriptNodeBuilder;

    protected function setUp(): void
    {
        $this->scriptNodeBuilder = new ScriptNodeBuilder();
        parent::setUp();
    }

    public function testRootEchoNodeToString()
    {
        $testNode = $this->scriptNodeBuilder::getRootEchoNode();
        self::assertEquals("Echo", (string) $testNode);
    }

    public function testPrivateChannelNodeToString()
    {
        $testNode = $this->scriptNodeBuilder::getPrivateChannelNode("App.Model.User.1");
        self::assertEquals(".private(App.Model.User.1)", (string) $testNode);

        $testNode = $this->scriptNodeBuilder::getPrivateChannelNode("`App.Model.User.\${window.test.userId}`");
        self::assertEquals(".private(`App.Model.User.\${window.test.userId}`)", (string) $testNode);
    }

    public function testListenNodeToString()
    {
        $testNode = $this->scriptNodeBuilder::getListenNode("ToastEvent", "function() {console.log('test');}");
        self::assertEquals(".listen('ToastEvent', function() {console.log('test');})", (string) $testNode);
    }

    public function testChannelContextNodeToString()
    {
        $testNode = $this->scriptNodeBuilder::getChannelContextNode("42", [
            'userId' => 1,
            'gameId' => 42,
        ]);

        self::assertEquals(<<<EXPECTED
42: {userId: 1,\r
gameId: 42}
EXPECTED
        , (string) $testNode);
    }
}
