<?php

namespace MallardDuck\DynamicEcho;

use MallardDuck\DynamicEcho\Loader\EventContractLoader;

class DynamicEchoService
{

    /**
     * @var EventContractLoader
     */
    private EventContractLoader $loader;

    public function __construct()
    {
        $this->loader = new EventContractLoader();
    }

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
}
