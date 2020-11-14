<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use MallardDuck\DynamicEcho\Contracts\ImplementsDynamicEcho;
use MallardDuck\DynamicEcho\Traits\DynamicEchoChannel;

class ToastEvent implements ShouldBroadcastNow, ImplementsDynamicEcho
{
    use Dispatchable, InteractsWithSockets, SerializesModels, DynamicEchoChannel;

    public int $userId;

    public string $type;

    public string $message;

    /**
     * Create a new event instance.
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
