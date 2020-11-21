<?php

namespace MallardDuck\DynamicEcho\Commands;

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
        $this->info("Starting installer for example events.");
        (new Filesystem())->ensureDirectoryExists(app_path('Events'));

        $this->info("Installing ConsoleLogEvent.");
        copy(__DIR__ . '/../../../examples/app/Events/ConsoleLogEvent.php', app_path('Events/ConsoleLogEvent.php'));
        $this->info("Installing ToastEvent.");
        copy(__DIR__ . '/../../../examples/app/Events/ToastEvent.php', app_path('Events/ToastEvent.php'));
        $this->info("Examples installed.");
        return 0;
    }
}
