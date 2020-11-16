<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Support\Collection;
use MallardDuck\DynamicEcho\ChannelManager;
use MallardDuck\DynamicEcho\Contracts\HasDynamicChannelFormula;

// TODO: Consider splitting channel loading into new loader class.
// TODO: Or, make this class good at loading channels and events - then renaming it.
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

        // TODO: Make the channel manager actually manage the channels fully.
        $channelManager = $this->channelManager;

        $events->each(static function ($val, $key) use ($channelManager, $baseNamespace) {
            $implements = class_implements($key);
            if (array_key_exists(HasDynamicChannelFormula::class, $implements)) {
                $eventName = str_replace($baseNamespace . '\\', '', $key);
                $channelParameterClass = $key::getChannelParametersClassname();
                $channelManager->pushEventDto(LoadedEventDTO::new(
                    $eventName,
                    $key,
                    new $channelParameterClass()
                ));
            }
        });
        gc_mem_caches();

        return $this->channelManager->registeredChannels();
    }
}
