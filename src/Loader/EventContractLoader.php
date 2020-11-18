<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MallardDuck\DynamicEcho\ChannelManager;
use MallardDuck\DynamicEcho\Channels\AbstractChannelParameters;
use MallardDuck\DynamicEcho\Contracts\HasDynamicChannelFormula;

// TODO: Make this class good at loading channels and events - then rename it properly.
class EventContractLoader
{
    /**
     * @var Collection
     */
    private Collection $appEvents;

    /**
     * @var ChannelManager
     */
    private ChannelManager $channelManager;

    public function __construct(ChannelManager $channelManager, string $namespace)
    {
        $this->channelManager = $channelManager;
        $this->appEvents = collect(require(app()->basePath() . '/vendor/composer/autoload_classmap.php'))
                                ->filter(static function ($val, $key) use ($namespace) {
                                    return str_starts_with($key, $namespace);
                                });
    }

    public function load(): ChannelAwareEventCollection
    {
        $events = $this->appEvents;
        $channelManager = $this->channelManager;

        $events->filter(static function ($val, $key) {
            $implements = class_implements($key);
            if (array_key_exists(HasDynamicChannelFormula::class, $implements)) {
                return true;
            }
            return false;
        })->each(static function ($val, $key) use ($channelManager) {
            /** @var AbstractChannelParameters $channelParameterClass */
            $channelParameterClass = $key::getChannelParametersClassname();
            $channelManager->pushEventDto(LoadedEventDTO::new(
                (string) Str::of($key)->afterLast("\\"),
                $key,
                $key::getEventJSCallback(),
                $channelParameterClass::getInstance()
            ));
        });
        gc_mem_caches();

        return $this->channelManager->getChannelEventCollection();
    }
}
