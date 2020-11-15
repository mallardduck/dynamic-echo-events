<?php

namespace MallardDuck\DynamicEcho\Channels;

use BadMethodCallException;
use Illuminate\Broadcasting\Channel;

/**
 * Trait UsesDynamicChannelFormula
 *
 * @implements DynamicChannelFormula
 * @package MallardDuck\DynamicEcho\Channels
 */
trait UseDynamicChannelFormula
{
    public AbstractChannelParameters $dynamicChannel;

    /**
     * @example:
     *         'App.Models.Game.{gameID}.User.{userID}'
     *
     * @return string
     */
    public function getChannelIdentifierFormula(): string
    {
        return $this->dynamicChannel->channelIdentifierFormula;
    }

    /**
     * @example:
     *         'App.Models.Game.42.User.1'
     *
     * @return string
     */
    public function getChannelIdentifier(): string
    {
        $bindings = $this->getChannelIdentifierBindings();
        $identifierFormula = $this->getChannelIdentifierFormula();

        foreach ($bindings as $binding => $value) {
            $identifierFormula = str_replace("{" . $binding . "}", $value, $identifierFormula);
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
    public function getJSChannelIdentifier(): string
    {
        return str_replace('{', '${', $this->getChannelIdentifierFormula());
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
        throw new BadMethodCallException("Method must be implemented by base class.");
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
        return $this->dynamicChannel->channelType;
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
