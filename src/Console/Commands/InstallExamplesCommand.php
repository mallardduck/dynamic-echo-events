<?php

namespace MallardDuck\DynamicEcho\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallExamplesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-echo:examples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the example event classes for an easy start.';

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
     * @return int
     */
    public function handle(): int
    {
        (new Filesystem)->ensureDirectoryExists(app_path('Events'));

        copy(__DIR__.'/../../../examples/app/Events/ConsoleLogEvent.php', app_path('Events/ConsoleLogEvent.php'));
        copy(__DIR__.'/../../../examples/app/Events/ToastEvent.php', app_path('Events/ToastEvent.php'));
        return 0;
    }
}
