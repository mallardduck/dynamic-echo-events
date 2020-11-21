<?php

namespace MallardDuck\DynamicEcho\Composer;

use Composer\Script\Event;
use Illuminate\Foundation\Application;

class ScriptsHelper
{
    /**
     * Handle the post-install Composer event.
     *
     * @param Event $event
     * @return void
     */
    public static function postInstall(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';
        static::clearCompiled();
    }

    /**
     * Handle the post-update Composer event.
     *
     * @param Event $event
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';
        static::clearCompiled();
    }

    /**
     * Handle the post-autoload-dump Composer event.
     *
     * @param Event  $event
     * @return void
     */
    public static function postAutoloadDump(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';
        static::clearCompiled();
    }

    protected static function clearCompiled()
    {
        $laravel = new Application(getcwd());
        // For now just clear the main cache
        if (is_file($servicesPath = $laravel->bootstrapPath('cache/dynamic-echo-discovery.php'))) {
            @unlink($servicesPath);
        }
        // TODO: clear route cache for the broadcast channels.
    }
}
