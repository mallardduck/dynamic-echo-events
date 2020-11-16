<?php

namespace MallardDuck\DynamicEcho;

use MallardDuck\DynamicEcho\Loader\EventContractLoader;
use MallardDuck\DynamicEcho\ScriptGenerator\ScriptNodeBuilder;

class DynamicEchoService
{
    /**
     * @var EventContractLoader
     */
    private EventContractLoader $loader;

    /**
     * @var ChannelManager
     */
    private ChannelManager $channelManager;

    /**
     * @var ScriptGenerator
     */
    private ScriptGenerator $scriptGenerator;

    public function __construct(EventContractLoader $loader, ScriptGenerator $scriptGenerator)
    {
        $this->loader = $loader;
        $this->channelManager = new ChannelManager();
        $this->loader->setChannelManager($this->channelManager);
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
        $warning = null;
        $generatedContext = $this->scriptGenerator->rootContext();

        return view('dynamicEcho::context', compact('warning', 'generatedContext'))->render();
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
        $warning = null;
        $loaderItems = $this->loader->load();

        // TODO: Allow multiple channels.
        $this->scriptGenerator->pushScriptNode(ScriptNodeBuilder::getPrivateChannelNode(
            '`App.Models.User.${window.dynamicEchoOld.userID}`'
        ));

        foreach ($loaderItems as $key => $item) {

            dd(
                $key,
                $item,
            );

            $this->scriptGenerator->pushScriptNode(ScriptNodeBuilder::getListenNode(
                $item->eventName,
                $item->jsCallback
            ));
        }
        // END TO DO

        $generatedScript = $this->scriptGenerator->rootScript();

        return view('dynamicEcho::scripts', compact('warning', 'generatedScript'))->render();
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

    public function getUsedChannels(): array
    {
        return $this->channelManager->registeredChannels();
    }

}
