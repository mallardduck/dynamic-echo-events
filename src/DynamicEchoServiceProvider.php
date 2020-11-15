<?php

namespace MallardDuck\DynamicEcho;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class DynamicEchoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerDynamicEchoSingleton();
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

    protected function registerDynamicEchoSingleton(): void
    {
        $this->app->singleton('dynamic-echo', DynamicEchoService::class);
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
        // TODO: Split this up into two halves - one that's static and the same for every user, the other dynamic for each.
        Blade::directive('dynamicEcho', [DynamicEchoBladeDirectives::class, 'dynamicEchoScripts']);
    }

    protected function registerEchoChannels(): void
    {
        Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
            return (int) $user->id === (int) $id;
        });
    }
}
