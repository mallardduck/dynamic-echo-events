<?php

namespace MallardDuck\DynamicEcho\Tests\Unit\ScriptGenerator\Nodes;

use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ContextNode;
use MallardDuck\DynamicEcho\Tests\BaseTest;

class ContextNodeTest extends BaseTest
{
    public function testContextNodeNotExtendedException()
    {
        self::expectException(\BadMethodCallException::class);
        $node = new ContextNode();
        json_encode($node);
    }

    public function testContextNodeNotExtendedExceptionMessage()
    {
        $node = new ContextNode();
        try {
            json_encode($node);
        } catch (\Exception $e) {
            // phpcs:disable
            self::assertEquals(
                "Method [jsonSerialize] must be implemented by concrete class [MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ContextNode]",
                $e->getMessage()
            );
            // phpcs:enable
        }
    }

    public function testExtendingContextNodeException()
    {
        self::expectException(\BadMethodCallException::class);
        $node = new TestContextNode();
        json_encode($node);
    }

    public function testExtendingContextNodeExceptionMessage()
    {
        $node = new TestContextNode();
        try {
            json_encode($node);
        } catch (\Exception $e) {
            self::assertStringMatchesFormat(
                "Method [jsonSerialize] must be implemented by concrete class [%s]",
                $e->getMessage()
            );
        }
    }
}

// phpcs:disable
class TestContextNode extends ContextNode
{
}
// phpcs:enable
