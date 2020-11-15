<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use MallardDuck\DynamicEcho\Contracts\ImplementsDynamicEcho;
use MallardDuck\DynamicEcho\Traits\PrivateDynamicEchoChannel;

class ToastEvent implements ShouldBroadcastNow, ImplementsDynamicEcho
{
    use Dispatchable, InteractsWithSockets, SerializesModels, PrivateDynamicEchoChannel;

    public int $userId;

    /**
     * Must be a string matching toastr toast type.
     *
     * Options:
     *  - info
     *  - warning
     *  - success
     *  - error
     *
     * @var string
     */
    public string $type;

    /**
     * A message text string for the toast notification.
     *
     * @var string
     */
    public string $message;

    /**
     * Create a new ToastEvent instance.
     *
     * The best way to do this is just using the event helper method.
     * So something like: event(new ToastEvent('success', 'Item created successfully!'))
     *
     * @return void
     */
    public function __construct(string $type, string $message)
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $this->userId = $user->id;
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Get the JS callback for this event.
     *
     * In this case, we're using toastr JS library to fire off a toast notification.
     * This obviously requires loading in the library tho, so either include or compile it in.
     * See: https://github.com/CodeSeven/toastr
     *
     * @return string
     */
    public static function getEventJSCallback(): string
    {
        return <<<JSCALLBACK
(e) => {
    const type = e.type;
    const message = e.message;
    window.toastr[type](message);
}
JSCALLBACK;
    }

}
