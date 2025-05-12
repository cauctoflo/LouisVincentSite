<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CreateModuleCommand::class,
        Commands\MoveControllerToModuleCommand::class,
        Commands\MoveModelToModuleCommand::class,
        Commands\MoveMigrationToModuleCommand::class,
        Commands\MoveViewToModuleCommand::class,
        Commands\MakeModuleModelCommand::class,
        Commands\MakeModuleMigrationCommand::class,
        Commands\MakeModuleControllerCommand::class,
        Commands\MakeModuleViewCommand::class,
        Commands\MakeModuleServiceCommand::class,
        Commands\DeleteModuleCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 