<?php

namespace MallardDuck\DynamicEcho\Contracts;

interface ImplementsDynamicEcho
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn();

    /**
     * A method that outputs the JS callback for the Echo event handler.
     *
     * Ideally you should use PHP's heredoc to write a blog of JS code.
     * You can use either modern arrow functions or a traditional callback.
     *
     * @return string
     */
    public static function getEventJSCallback(): string;
}
