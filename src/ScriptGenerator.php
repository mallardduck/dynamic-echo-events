<?php

namespace MallardDuck\DynamicEcho;

use Illuminate\Support\Collection;
use MallardDuck\DynamicEcho\ScriptGenerator\ContextNodeCollection;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ContextNode;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ScriptNode;
use MallardDuck\DynamicEcho\ScriptGenerator\ScriptNodeBuilder;

/**
 * Class ScriptGenerator
 *
 * Generates the Echo javascript for both the context and event scripts.
 *
 * @package MallardDuck\DynamicEcho
 */
class ScriptGenerator
{
    private ContextNodeCollection $contextNodeStack;
    private Collection $scriptNodeStack;

    public function __construct()
    {
        $this->contextNodeStack = new ContextNodeCollection();

        $this->scriptNodeStack = new Collection([
            ScriptNodeBuilder::getRootEchoNode(),
        ]);
    }

    public function pushContextNode(ContextNode $node): self
    {
        $this->contextNodeStack->push($node);
        return $this;
    }

    public function getRootContext(): string
    {
        return $this->contextNodeStack->toJson();
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
    public function getRootScript(): string
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
