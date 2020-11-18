<?php

namespace MallardDuck\DynamicEcho;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use MallardDuck\DynamicEcho\Loader\EventContractLoader;
use MallardDuck\DynamicEcho\ScriptGenerator\EchoScriptGenerator;

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
        $this->app->singleton(EventContractLoader::class, static function ($app) {
            return new EventContractLoader(
                $app->config->get('dynamic-echo.namespace', "App\\Events")
            );
        });

        $this->app->singleton(EchoScriptGenerator::class, static function () {
            return new EchoScriptGenerator();
        });

        $this->app->singleton(DynamicEchoService::class, static function ($app) {
            return new DynamicEchoService(
                $app->make(EventContractLoader::class),
                $app->make(EchoScriptGenerator::class)
            );
        });

        $this->app->alias(DynamicEchoService::class, 'dynamic-echo');
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
        Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
            return (int) $user->id === (int) $id;
        });
    }
}
