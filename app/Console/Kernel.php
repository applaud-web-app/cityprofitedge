<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('oms-config:command')->everyMinute()->between('8:00','20:00')->sendOutputTo('command1_output.log');
        $schedule->command('angel_instrument:daily_update')->dailyAt('10:10')->sendOutputTo('command2_output.log');
        // $schedule->command('angleHistorical:every_minute')->everyMinute()->sendOutputTo('command3_output.log');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
