<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Support\Collection;
use MallardDuck\DynamicEcho\Channels\ChannelManager;
use MallardDuck\DynamicEcho\Contracts\ImplementsDynamicEcho;

class EventContractLoader
{

    /**
     * @var string
     */
    private $baseNamespace;

    /**
     * @var Collection
     */
    private Collection $appEvents;

    /**
     * @var ChannelManager
     */
    private ChannelManager $channelManager;

    public function __construct()
    {
        $this->baseNamespace = $baseNamespace = config('dynamic-echo.namespace', "App\\Events");
        $this->appEvents = collect(require(app()->basePath() . '/vendor/composer/autoload_classmap.php'))
                                ->filter(static function ($val, $key) use ($baseNamespace) {
                                    return str_starts_with($key, $baseNamespace);
                                });
    }

    public function setChannelManager(ChannelManager $channelManager): self
    {
        $this->channelManager = $channelManager;
        return $this;
    }

    public function load(): array
    {
        $events = $this->appEvents;
        $baseNamespace = $this->baseNamespace;
        // TODO: Actually use channel manager for the events we find.
        $channelManager = $this->channelManager;

        $collection = new Collection();


        $events->each(static function ($val, $key) use ($collection, $baseNamespace) {
            $implements = class_implements($key);
            if (array_key_exists(ImplementsDynamicEcho::class, $implements)) {
                $collection->push([
                    'event' => str_replace($baseNamespace . '\\', '', $key),
                    'js-handler' => $key::getEventJSCallback()
                ]);
            }
        });
        gc_mem_caches();

        return $collection->toArray();
    }
}
