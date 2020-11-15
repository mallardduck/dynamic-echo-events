<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator;

use Illuminate\Support\Collection;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\RootEchoNode;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ScriptNode;

/**
 * Class EchoScriptGenerator
 *
 * Generates the Echo javascript for both the context and event scripts.
 *
 * @package MallardDuck\DynamicEcho
 */
class EchoScriptGenerator
{
    private Collection $scriptNodeStack;

    public function __construct()
    {
        $this->scriptNodeStack = new Collection([
            new RootEchoNode(),
        ]);
    }

    public function rootContext(): string
    {
        // TODO: figure out how to fetch/build the root context.
        return '';
    }

    public function pushScriptNode(ScriptNode $node): self
    {
        $this->scriptNodeStack->push($node);
        return $this;
    }

    public function rootScript(): string
    {
        return $this->renderNodeStack($this->scriptNodeStack);
    }

    private function renderNodeStack(Collection $nodeStack): string
    {
        /**
         * @var string
         */
        $results = '';
        $count = $nodeStack->count();
        $nodeStack->map(static function ($item) use (&$results, &$count) {
            $results .= (string) $item;
            --$count;
            if (0 === $count) {
                $results .= ";\n";
            } else {
                $results .= "\n";
            }
        });

        return $results;
    }
}
