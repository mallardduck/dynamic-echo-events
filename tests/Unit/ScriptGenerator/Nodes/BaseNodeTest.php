<?php

namespace MallardDuck\DynamicEcho\Tests\Unit\ScriptGenerator\Nodes;

use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\BaseNode;
use MallardDuck\DynamicEcho\Tests\BaseTest;

class BaseNodeTest extends BaseTest
{
    public function testBaseNodeAbstractException()
    {
        self::expectException(\Error::class);
        $node = new BaseNode();
    }

    public function testNotImplementedExtendedExceptoin()
    {
        self::expectException(\BadMethodCallException::class);
        $node = new TestBaseNode();
        (string) $node;
    }
}

// phpcs:disable
class TestBaseNode extends BaseNode
{
}
// phpcs:enable
