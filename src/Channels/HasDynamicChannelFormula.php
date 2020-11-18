<?php

namespace MallardDuck\DynamicEcho\Channels;

use BadMethodCallException;
use Illuminate\Broadcasting\Channel;

interface HasDynamicChannelFormula
{
    /**
     * A static method to get the classname of the channel's AbstractChannelParameters.
     *
     * The best return would be to return the string using the ::class selector.
     * So you'd include the trait via use, then define this method yourself.
     *
     * @example:
     *         ToastChannel::class
     *
     * @return string
     * @throws BadMethodCallException
     */
    public static function getChannelParametersClassname(): string;

    /**
     * A method to get the broadcast channels name formula.
     *
     * The name formula is essentially a route formual, but for broadcast channels.
     * Must be implemented by base Event class using the dynamic echo events.
     *
     * @example:
     *         'App.Models.Game.{gameID}.User.{userID}'
     *
     * @return string
     * @throws BadMethodCallException
     */
    public static function getChannelIdentifierFormula(): string;

    /**
     * @example:
     *         'App.Models.Game.42.User.1'
     *
     * @return string
     */
    public function getChannelIdentifier(): string;

    /**
     * @example:
     *         'App.Models.Game.${gameID}.User.${userID}'
     *
     * @return string
     */
    public static function getJSChannelIdentifier(): string;

    /**
     * A method to get the binding data for this Event's channel.
     *
     * Must be implemented by base Event class using the dynamic echo events.
     *
     * @return array
     * @throws BadMethodCallException
     *
     * @example:
     * [
     *     'gameID' => $this->gameId
     *     'userID' => $this->userId
     * ]
     */
    public function getChannelIdentifierBindings(): array;

    /**
     * A class string for the type of event channel to use
     *
     * Must be implemented by base Event class using the dynamic echo events.
     *
     * @example Options as follows:
     *  @see \Illuminate\Broadcasting\Channel
     *  @see \Illuminate\Broadcasting\PrivateChannel
     *
     * @return string
     * @throws BadMethodCallException
     */
    public function getChannelType(): string;

    /**
     * This method takes the Formula class and builds the channel.
     *
     * @return Channel
     */
    public function buildChannelFormula(): Channel;
}
