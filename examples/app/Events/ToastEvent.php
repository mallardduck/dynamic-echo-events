<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use MallardDuck\DynamicEcho\Channels\{
    BaseDynamicChannelFormula,
    PrivateUserChannelParameters,
};
use MallardDuck\DynamicEcho\Contracts\{
    HasDynamicChannelFormula,
    ImplementsDynamicEcho,
};

/**
 * Class ToastEvent
 *
 * @mixin HasDynamicChannelFormula
 * @mixin BaseDynamicChannelFormula
 */
class ToastEvent implements ShouldBroadcastNow, ImplementsDynamicEcho, HasDynamicChannelFormula
{
    use Dispatchable, InteractsWithSockets, BaseDynamicChannelFormula;

    public int $userId;

    public string $type;

    public string $message;

    /**
     * Create a new event instance.
     *
     * @param string   $type
     * @param string   $message
     * @param null|int $userId
     */
    public function __construct(string $type, string $message, ?int $userId = null)
    {
        $this->type = $type;
        $this->message = $message;
        $this->userId = $userId ?? Auth::user()->id;
    }

    public static function getChannelParametersClassname(): string
    {
        return PrivateUserChannelParameters::class;
    }

    public static function getEventJSCallback(): string
    {
        return <<<JSCALLBACK
(e) => {
    console.log(`User: \${e.userId} received a "\${e.type}" type toast event.`);
    const type = e.type;
    const message = e.message;
    window.toastr[type](message);
}
JSCALLBACK;
    }
}
