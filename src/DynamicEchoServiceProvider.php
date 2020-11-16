<?php

namespace MallardDuck\DynamicEcho;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
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
        $this->registerProviders();
        $this->mergeConfigFrom(__DIR__ . '/../config/dynamic-echo.php', 'dynamic-echo');
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
    }

    protected function registerProviders(): void
    {
        $this->app->singleton(ChannelManager::class, static function () {
            return new ChannelManager();
        });

        $this->app->singleton(EventContractLoader::class, static function ($app) {
            return new EventContractLoader(
                $app->config->get('dynamic-echo.namespace', "App\\Events"),
                $app->make(ChannelManager::class)
            );
        });

        $this->app->singleton(ScriptGenerator::class, static function () {
            return new ScriptGenerator();
        });

        $this->app->singleton(DynamicEchoService::class, static function ($app) {
            return new DynamicEchoService(
                $app->make(EventContractLoader::class),
                $app->make(ScriptGenerator::class)
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
        $loader = $this->app->make(EventContractLoader::class);
        $channels = $loader->load();

        foreach ($channels as $channelName => $channelEvents) {
            // TODO: Use $className to register channels.
            // TODO: Find a way to get the channel callback.
            dd(
                $channelName,
                $channelEvents
            );
        }

        // TODO: Make these generated by the channel manager stuff too.
        Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
            return (int) $user->id === (int) $id;
        });
    }
}
