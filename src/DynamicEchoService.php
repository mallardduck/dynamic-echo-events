<?php

namespace MallardDuck\DynamicEcho;

use MallardDuck\DynamicEcho\Loader\ChannelEventCollection;
use MallardDuck\DynamicEcho\Loader\EventContractLoader;
use MallardDuck\DynamicEcho\Loader\LoadedEventDTO;
use MallardDuck\DynamicEcho\ScriptGenerator\ScriptNodeBuilder;

class DynamicEchoService
{
    /**
     * @var ChannelManager
     */
    private ChannelManager $channelManager;

    /**
     * @var ScriptGenerator
     */
    private ScriptGenerator $scriptGenerator;

    public function __construct(
        ChannelManager $channelManager,
        ScriptGenerator $scriptGenerator
    ) {
        $this->channelManager = $channelManager;
        $this->scriptGenerator = $scriptGenerator;
    }

    public function context(): string
    {
        $debug = config('app.debug');

        $context = $this->compiledJSContext();

        $html = $this->buildHtmlStack($context, 'Context');

        return implode("\n", $html);
    }

    /**
     * Internally compile the necessary JS context for the current Request/User.
     *
     * This method calls on the ChannelManager to fetch channels and events that need to be registered.
     * Then it pushes the equivalent ScriptNodes into the generator and creates the JS listeners.
     *
     * @return string
     */
    protected function compiledJSContext(): string
    {
        $warning = null;


        /**
         * @var ChannelManager $channelManager
         */
        $channelManager = $this->channelManager;

        /**
         * @var ChannelEventCollection $channelGroup
         */
        foreach ($channelManager->getChannelEventCollection() as $channelName => $channelGroup) {
            // TODO: Figure out how I'll really push the context nodes.
            // TODO: Does the creation of the contextNodes -here- push data *somewhere* that the scriptNodes depend on?
            $this->scriptGenerator->pushContextNode(ScriptNodeBuilder::getRootContextNode(
                "context shit"
            ));
        }



        $generatedContext = $this->scriptGenerator->getRootContext();

        return view('dynamicEcho::context', compact('warning', 'generatedContext'))->render();
    }

    public function scripts(): string
    {
        $debug = config('app.debug');

        $scripts = $this->compiledJSScripts();

        $html = $this->buildHtmlStack($scripts, 'Scripts');

        return implode("\n", $html);
    }

    /**
     * Internally compile the necessary JS code to subscribe to channels and events.
     *
     * This method calls on the ChannelManager to fetch channels and events that need to be registered.
     * Then it pushes the equivalent ScriptNodes into the generator and creates the JS listeners.
     *
     * @return string
     */
    protected function compiledJSScripts(): string
    {
        $warning = null;
        /**
         * @var ChannelManager $channelManager
         */
        $channelManager = $this->channelManager;

        /**
         * @var ChannelEventCollection $channelGroup
         */
        foreach ($channelManager->getChannelEventCollection() as $channelName => $channelGroup) {
            // Push the channel subscription in first...
            $this->scriptGenerator->pushScriptNode(ScriptNodeBuilder::getPrivateChannelNode(
                $channelGroup->getChannelJsIdentifier()
            ));

            /**
             * @var LoadedEventDTO $eventDTO
             */
            foreach ($channelGroup as $key => $eventDTO) {
                // Then push each of the channels events into the stack too.
                $this->scriptGenerator->pushScriptNode(ScriptNodeBuilder::getListenNode(
                    $eventDTO->eventName,
                    $eventDTO->jsEventCallback
                ));
            }
        }

        $generatedScript = $this->scriptGenerator->getRootScript();

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
