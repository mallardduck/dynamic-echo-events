<?php

namespace MallardDuck\DynamicEcho;

use MallardDuck\DynamicEcho\Channels\ChannelManager;
use MallardDuck\DynamicEcho\Loader\EventContractLoader;

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

    public function __construct()
    {
        $this->loader = new EventContractLoader();
        $this->channelManager = new ChannelManager();
        $this->loader->setChannelManager($this->channelManager);
    }

    // TODO: Split script output up into static and dynamic content.
    // NOTE: Ideally the static content could be "compiled" more consistently to a cache.
    // CONT: Then the dynamic ones would just be injected to the page for each user's request.
    public function scripts(): string
    {
        $debug = config('app.debug');

        $scripts = $this->compiledJSAssets();

        $html = [];

        if ($debug) {
            $html[] = '<!-- Start DynamicEcho Scripts -->';
        }
        $html[] = $debug ? $scripts : $this->minify($scripts);
        if ($debug) {
            $html[] = '<!-- End DynamicEcho Scripts -->';
        }

        return implode("\n", $html);
    }

    // TODO: in the future this one would only render the static JS from dynamic Echos.
    protected function compiledJSAssets(): string
    {
        $assetWarning = null;
        $loaderItems = $this->loader->load();

        if (0 === count($loaderItems)) {
            return '';
        }

        return view('dynamicEcho::basicJs', compact('assetWarning', 'loaderItems'))->render();
    }

    protected function minify(string $subject): string
    {
        return preg_replace('~(\v|\t|\s{2,})~m', '', $subject);
    }

    public function getUsedChannels()
    {
        return $this->channelManager->registeredChannels();
    }

}
