<?php

namespace MallardDuck\DynamicEcho\Channels;

abstract class AbstractChannelParameters
{
    /**
     *  A class name for the type of channel this will use.
     *
     *  This should most often be defined using a `::class` reference.
     *
     * @var string
     */
    public string $channelType;

    /**
     * The channel's route name with bindings.
     *
     * This supports the same route binding style laravel supports in normal web routes.
     * So it can either be a static, but unique string; or a dynamic route with bindings.
     *
     * @var string
     */
    public string $channelAuthName;

    /**
     * This needs to be either a callable to check authorization, or a Channel class name.
     *
     * Your callback should do a authorization check to see if the authenticated user is allowed to use the channel.
     *
     * @var callable|string
     */
    // TODO: remove string/class name option once this package supports Channel based discovery.
    public $channelAuthCallback;

    /**
     * An array of channel options to be used.
     *
     * Most often used to adjust guard settings for a single channel.
     *
     * @var array
     */
    public array $channelAuthOptions = [];

    /**
     * This callable that will resolve the channel name bindings for the event.
     *
     * The callback will be passed the event it's related to and have access to the public properties and methods.
     * Using those from the event, how would the dynamic bindings be resolved to the specific requests' values.
     *
     * @var callable
     */
    public $eventChannelIdentifierBindingCallback;

    /**
     * A representation of the $channelAuthName intended for the browser context.
     *
     * This property is automatically generated from the $channelAuthName on class construction.
     * This should automagically turn into the true JS look-up formula by the time it's rendered.
     *
     * @var string
     */
    public string $channelJsIdentifier;

    private static $parametersInstances = [];

    /**
     * AbstractChannelParameters constructor.
     *
     * @note Must be called by implementing class.
     */
    protected function __construct()
    {
        $this->channelJsIdentifier = $this->getJSChannelIdentifier();
    }

    public static function getInstance()
    {
        $class = static::class;
        if (!isset(self::$parametersInstances[$class])) {
            self::$parametersInstances[$class] = new $class();
        }

        return self::$parametersInstances[$class];
    }

    public function getJSChannelIdentifier(): string
    {
        // TODO: Maybe make this configurable?
        $baseJsVarScope = "window.dynamicEcho";
        $baseJsVar = sprintf("%s['%s'].", $baseJsVarScope, md5($this->channelAuthName));
        // Replace the Laravel braces with JS braces and variable roots.
        $jsRouteTemplate = str_replace('{', '${'.$baseJsVar, $this->channelAuthName);
        // Wrap the string in backticks/graves for JS template literals.
        return "`$jsRouteTemplate`";
    }
}
