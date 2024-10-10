<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // First backup at 12:00 PM
        $schedule->command('backup:clean')->dailyAt('11:30');
        $schedule->command('backup:run')->dailyAt('12:00');

        // Second backup at 3:30 AM
        $schedule->command('backup:clean')->dailyAt('03:00');
        $schedule->command('backup:run')->dailyAt('03:30');
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
