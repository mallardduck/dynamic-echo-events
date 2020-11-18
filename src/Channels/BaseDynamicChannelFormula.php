<?php

namespace MallardDuck\DynamicEcho\Channels;

use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Trait UsesDynamicChannelFormula
 *
 * @implements HasDynamicChannelFormula
 * @package MallardDuck\DynamicEcho\Channels
 */
trait BaseDynamicChannelFormula
{
    protected static ?AbstractChannelParameters $dynamicChannel = null;

    public static function getChannelParametersClassname(): string
    {
        throw new \BadMethodCallException("This method should be redefined by the base class.");
    }

    private static function getChannelParametersClass(): AbstractChannelParameters
    {
        if (null === self::$dynamicChannel) {
            self::$dynamicChannel = self::getChannelParametersClassname()::getInstance();
        }

        return self::$dynamicChannel;
    }

    /**
     * @example:
     *         'App.Models.Game.{gameID}.User.{userID}'
     *
     * @return string
     */
    public static function getChannelAuthName(): string
    {
        return self::getChannelParametersClass()->channelAuthName;
    }

    /**
     * @example:
     *         'App.Models.Game.42.User.1'
     *
     * @return string
     * @throws RuntimeException
     */
    public function getChannelIdentifier(): string
    {
        $bindings = $this->getChannelIdentifierBindings();
        $identifierFormula = self::getChannelAuthName();


        foreach ($bindings as $binding => $value) {
            $identifierFormula = Str::replaceFirst("{" . $binding . "}", $value, $identifierFormula);
        }

        if (Str::contains($identifierFormula, ["{", "}"])) {
            throw new RuntimeException(sprintf(
                "Fatal Error: Channel identifier [%s] bindings not properly replaced. Still contains curly braces.",
                $identifierFormula
            ));
        }

        return $identifierFormula;
    }

    /**
     * Rewrites the identifier for JS channel listener.
     *
     * Essentially replaces the dynamic bindings with "${" instead of "{".
     *
     * @example:
     *         'App.Models.Game.${gameID}.User.${userID}'
     *
     * @return string
     */
    public static function getJSChannelIdentifier(): string
    {
        return str_replace('{', '${', self::getChannelAuthName());
    }

    /**
     * @return array
     *
     * @example:
     * [
     *     'gameID' => $this->gameId
     *     'userID' => $this->userId
     * ]
     */
    public function getChannelIdentifierBindings(): array
    {
        $selfEvent = $this;
        $callback = self::getChannelParametersClass()->channelIdentifierBindingCallback;

        return $callback($selfEvent);
    }

    /**
     * A class string for the type of event channel to use.
     *
     * @example Options as follows:
     *  @see \Illuminate\Broadcasting\Channel
     *  @see \Illuminate\Broadcasting\PrivateChannel
     *
     * @return string
     */
    public function getChannelType(): string
    {
        return self::getChannelParametersClass()->channelType;
    }

    /**
     * This method takes the Formula class and builds the channel.
     *
     * @return Channel
     */
    public function buildChannelFormula(): Channel
    {
        $channelType = $this->getChannelType();
        $channelIdentifier = $this->getChannelIdentifier();

        return new $channelType($channelIdentifier);
    }

    public function broadcastOn(): Channel
    {
        return $this->buildChannelFormula();
    }
}
