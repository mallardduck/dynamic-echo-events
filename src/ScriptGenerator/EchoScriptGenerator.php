<?php

namespace MallardDuck\DynamicEcho\ScriptGenerator;

use Illuminate\Support\Collection;
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
            ScriptNodeBuilder::getRootEchoNode(),
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

    /**
     * Outputs the entire generated Echo script code.
     *
     * @return string
     */
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
