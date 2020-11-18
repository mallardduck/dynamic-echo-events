<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Support\Collection;
use MallardDuck\DynamicEcho\ChannelManager;
use MallardDuck\DynamicEcho\Channels\HasDynamicChannelFormula;

class EventContractLoader
{

    /**
     * @var string
     */
    private string $baseNamespace;

    /**
     * @var Collection
     */
    private Collection $appEvents;

    /**
     * @var Collection
     */
    private Collection $appEventChannels;

    /**
     * @var ChannelManager
     */
    private ChannelManager $channelManager;

    public function __construct(string $namespace, ChannelManager $channelManager)
    {
        $this->baseNamespace = $namespace;
        $this->channelManager = $channelManager;
        $this->appEvents = collect(require(app()->basePath() . '/vendor/composer/autoload_classmap.php'))
                                ->filter(static function ($val, $key) use ($namespace) {
                                    return str_starts_with($key, $namespace);
                                });
    }

    public function load(): array
    {
        $events = $this->appEvents;
        $baseNamespace = $this->baseNamespace;
        // TODO: Actually use channel manager for the events we find.
        $channelManager = $this->channelManager;

        $events->each(static function ($val, $key) use ($channelManager, $baseNamespace) {
            $implements = class_implements($key);
            if (array_key_exists(HasDynamicChannelFormula::class, $implements)) {
                $eventName = str_replace($baseNamespace . '\\', '', $key);
                $channelParameterClass = $key::getChannelParametersClassname();
                $channelParameterClass = new $channelParameterClass();
                // TODO: Make this loader aware of what channel these go to.
                $channelManager->pushEventDto(LoadedEventDTO::new(
                    $eventName,
                    $key,
                    $channelParameterClass->channelIdentifierFormula,
                    $channelParameterClass->channelIdentifierFormula,
                    $channelParameterClass->channelJsEventCallback,
                ));
            }
        });
        gc_mem_caches();

        return $this->channelManager->registeredChannels();
    }
}
