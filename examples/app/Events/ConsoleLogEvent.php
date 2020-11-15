<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use MallardDuck\DynamicEcho\Contracts\ImplementsDynamicEcho;
use MallardDuck\DynamicEcho\Traits\PrivateDynamicEchoChannel;

class ConsoleLogEvent implements ShouldBroadcastNow, ImplementsDynamicEcho
{
    use Dispatchable, InteractsWithSockets, SerializesModels, PrivateDynamicEchoChannel;

    public int $userId;

    /**
     * A message text string for the console log notification.
     *
     * @var string
     */
    public string $message;

    public function __construct(string $message)
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $this->userId = $user->id;
        $this->message = $message;
    }

    /**
     * Get the JS callback for this event.
     *
     * In this case, it simply pushes a message to the JS console log.
     *
     * @return string
     */
    public static function getEventJSCallback(): string
    {
        return <<<JSCALLBACK
(e) => {
    console.log(e.message);
}
JSCALLBACK;
    }

}
