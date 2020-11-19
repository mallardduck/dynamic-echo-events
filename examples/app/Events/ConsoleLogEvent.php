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

class ConsoleLogEvent implements ShouldBroadcastNow, ImplementsDynamicEcho, HasDynamicChannelFormula
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    use BaseDynamicChannelFormula;

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

    public static function getChannelParametersClassname(): string
    {
        return PrivateUserChannelParameters::class;
    }

    /**
     * Get the JS callback for this event.
     *
     * In this case, we're just shoving a message into console.log.
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
