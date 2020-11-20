<?php

namespace MallardDuck\DynamicEcho;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use MallardDuck\DynamicEcho\Collections\{ChannelAwareEventCollection, ChannelEventCollection};
use MallardDuck\DynamicEcho\Commands\InstallExamplesCommand;
use MallardDuck\DynamicEcho\Commands\PrintChannels;
use MallardDuck\DynamicEcho\Composer\CacheResolver;
use MallardDuck\DynamicEcho\Loader\EventContractLoader;

class DynamicEchoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerComposerResolver();
        $this->mergeConfigFrom(__DIR__ . '/../config/dynamic-echo.php', 'dynamic-echo');
        $this->registerServices();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConfigs();
        $this->registerViews();
        $this->registerBladeDirectives();
        $this->registerEchoChannels();
        $this->registerConsoleCommands();
    }

    protected function registerComposerResolver(): void
    {
        $this->app->singleton(CacheResolver::class, static function ($app) {
            return (new CacheResolver(
                $app->config->get('dynamic-echo.namespace', "App\\Events"),
                $app->bootstrapPath('cache/dynamic-echo-discovery.php')
            ));
        });
    }

    protected function registerServices(): void
    {
        $this->app->singleton(ChannelManager::class, static function () {
            return new ChannelManager();
        });

        $this->app->singleton(EventContractLoader::class, static function ($app) {
            return new EventContractLoader(
                $app->make(ChannelManager::class),
                $app->make(CacheResolver::class)->setFilesystem($app->make(Filesystem::class))->build()
            );
        });

        $this->app->singleton(ScriptGenerator::class, static function () {
            return new ScriptGenerator();
        });

        $this->app->singleton(DynamicEchoService::class, static function ($app) {
            return new DynamicEchoService(
                $app->make(ChannelManager::class),
                $app->make(ScriptGenerator::class),
            );
        });

        $this->app->alias(DynamicEchoService::class, 'dynamic-echo');
        $this->app->alias(ChannelManager::class, 'dynamic-echo::channel-manager');
    }

    protected function registerConfigs(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/dynamic-echo.php' => base_path('config/dynamic-echo.php'),
            ], 'config');
        }
    }

    protected function registerViews(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/dynamic-echo-events'),
            ], 'views');
        }

        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'dynamicEcho'
        );
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('dynamicEchoContext', [DynamicEchoBladeDirectives::class, 'dynamicEchoContext']);
        Blade::directive('dynamicEchoScripts', [DynamicEchoBladeDirectives::class, 'dynamicEchoScripts']);
    }

    protected function registerEchoChannels(): void
    {
        /**
         * @var EventContractLoader $loader
         */
        $eventContractLoader = $this->app->make(EventContractLoader::class);

        /**
         * @var ChannelAwareEventCollection $channels
         */
        $channels = $eventContractLoader->load();

        /**
         * @var string $channelName
         * @var ChannelEventCollection $channelGroup
         */
        foreach ($channels as $channelName => $channelGroup) {
            Broadcast::channel($channelGroup->getChannelAuthName(), $channelGroup->getChannelAuthCallback());
        }
    }

    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PrintChannels::class,
                InstallExamplesCommand::class,
            ]);
        }
    }
}
