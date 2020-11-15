<?php

namespace MallardDuck\DynamicEcho;

use MallardDuck\DynamicEcho\Loader\EventContractLoader;
use MallardDuck\DynamicEcho\ScriptGenerator\EchoScriptGenerator;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\ListenNode;
use MallardDuck\DynamicEcho\ScriptGenerator\Nodes\PrivateChannelNode;

class DynamicEchoService
{
    /**
     * @var EventContractLoader
     */
    private EventContractLoader $loader;

    /**
     * @var EchoScriptGenerator
     */
    private EchoScriptGenerator $scriptGenerator;

    public function __construct(EventContractLoader $loader, EchoScriptGenerator $scriptGenerator)
    {
        $this->loader = $loader;
        $this->scriptGenerator = $scriptGenerator;
    }

    public function context(): string
    {
        $debug = config('app.debug');

        $context = $this->compiledJSContext();

        $html = $this->buildHtmlStack($context, 'Context');

        return implode("\n", $html);
    }

    protected function compiledJSContext(): string
    {
        $assetWarning = null;

        return view('dynamicEcho::context', compact('assetWarning'))->render();
    }

    public function scripts(): string
    {
        $debug = config('app.debug');

        $scripts = $this->compiledJSScripts();

        $html = $this->buildHtmlStack($scripts, 'Scripts');

        return implode("\n", $html);
    }

    protected function compiledJSScripts(): string
    {
        $assetWarning = null;
        $loaderItems = $this->loader->load();

        // TODO: Allow multiple channels.
        $this->scriptGenerator->pushScriptNode(new PrivateChannelNode(
            '`App.Models.User.${window.dynamicEcho.userID}`'
        ));

        foreach ($loaderItems as $item) {
            $this->scriptGenerator->pushScriptNode(new ListenNode(
                $item['event'],
                $item['js-handler']
            ));
        }
        $generatedScript = $this->scriptGenerator->rootScript();

        return view('dynamicEcho::scripts', compact('assetWarning', 'generatedScript'))->render();
    }

    protected function buildHtmlStack(string $content, string $renderType): array
    {
        /**
         * @var ?bool $debug
         */
        static $debug;

        if (null === $debug) {
            $debug = config('app.debug');
        }

        if ($debug) {
            $html[] = '<!-- Start DynamicEcho ' . $renderType . ' -->';
        }
        $html[] = $debug ? $content : $this->minify($content);
        if ($debug) {
            $html[] = '<!-- End DynamicEcho ' . $renderType . ' -->';
        }

        return $html;
    }

    protected function minify(string $subject): string
    {
        return preg_replace('~(\v|\t|\s{2,})~m', '', $subject);
    }
}
