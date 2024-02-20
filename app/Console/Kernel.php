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
        $schedule->command('oms-config:command')->everyMinute()->sendOutputTo('command1_output.log');
        // $schedule->command('oms-config-rt:command')->everyMinute()->sendOutputTo('command5_output.log');
        $schedule->command('angel_instrument:daily_update')->dailyAt('9:00')->sendOutputTo('command2_output.log');
        $schedule->command('angleHistorical:every_minute')->everyMinute()->sendOutputTo('command3_output.log');
        $schedule->command('zerodha_instrument:insert')->dailyAt('08:30')->sendOutputTo('command4_output.log');
        // $schedule->command('store_market_data:store_data')->everyMinute()->sendOutputTo('command6_output.log');
        $schedule->command('place_limit_ordre:limitOrder')->everyMinute()->sendOutputTo('command7_output.log');
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
