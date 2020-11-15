<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Support\Collection;
use MallardDuck\DynamicEcho\Contracts\ImplementsDynamicEcho;

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

    public function __construct(string $namespace)
    {
        $this->baseNamespace = $namespace;
        $this->appEvents = collect(require(app()->basePath() . '/vendor/composer/autoload_classmap.php'))
                                ->filter(static function ($val, $key) use ($namespace) {
                                    return str_starts_with($key, $namespace);
                                });
    }

    public function load(): array
    {
        $events = $this->appEvents;
        $baseNamespace = $this->baseNamespace;

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
