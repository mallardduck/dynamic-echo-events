<?php

namespace MallardDuck\DynamicEcho\Tests\Unit\ScriptGenerator\Nodes;

use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\BaseNode;
use MallardDuck\DynamicEcho\Tests\BaseTest;

class BaseNodeTest extends BaseTest
{
    function testBaseNodeAbstractException()
    {
        self::expectException(\Error::class);
        $node = new BaseNode();
    }

    function testNotImplementedExtendedExceptoin()
    {
        self::expectException(\BadMethodCallException::class);
        $node = new TestBaseNode();
        (string) $node;
    }
}

class TestBaseNode extends BaseNode {}
