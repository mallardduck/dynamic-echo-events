<?php

namespace MallardDuck\DynamicEcho\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MallardDuck\DynamicEcho\DynamicEchoService;

class PrintChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-echo:channels {--F|full : Get the full channel report.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'An easy way to see what routes are discoverable by the DynamicEchoEvent services.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param DynamicEchoService $dynamicEchoService
     *
     * @return int
     */
    public function handle(DynamicEchoService $dynamicEchoService): int
    {
        $headers = ["Event Channel"];
        /**
         * @var Collection
         */
        $channelMap = collect([[]]);
        if (!$this->option('full')) {
            $channelMap = $dynamicEchoService->getDiscoveredChannels();
            $channelMap = $channelMap->map(static function ($val, $key) {
                return [$val];
            });
        } else {
            $headers[] = 'Channel Type';
            $headers[] = 'Auth Callback';
            $headers[] = 'Events';
            $channelMap = $dynamicEchoService->getExtendedChannelInfo();

            $channelMap = $channelMap->map(static function ($val, $key) {
                $authReflection = new \ReflectionFunction($val['authCallback']);
                $callbackType = Str::afterLast($authReflection->getName(), "\\");
                $args = array_map(static function ($val) {
                    if (null !== $val->getType()) {
                        return sprintf("%s %s", $val->getType()->getName(), $val->getName());
                    }
                    return $val->getName();
                }, $authReflection->getParameters());

                return [
                    $key,
                    $val['type'],
                    sprintf("%s - %s", $callbackType, implode(", ", $args)),
                    implode(",".PHP_EOL, $val['events']),
                ];
            });
        }

        $this->table($headers, $channelMap->toArray());
        return 0;
    }
}
