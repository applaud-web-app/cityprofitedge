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
        $schedule->command('oms-config-rt:command')->everyMinute()->sendOutputTo('command5_output.log');
        $schedule->command('angel_instrument:daily_update')->dailyAt('9:00')->sendOutputTo('command2_output.log');
        $schedule->command('zerodha_instrument:insert')->dailyAt('08:30')->sendOutputTo('command4_output.log');
        $schedule->command('place_limit_ordre:limitOrder')->everyMinute()->sendOutputTo('command7_output.log');

        // Don't Remove 
        // $schedule->command('angleHistorical:every_minute')->everyMinute()->sendOutputTo('command3_output.log');
        // $schedule->command('store_market_data:store_data')->everyMinute()->sendOutputTo('command6_output.log');


        // New Crons
        $schedule->command('crudeoil:every_minute')->everyMinute()->sendOutputTo('command10_output.log');
        $schedule->command('banknifty:every_minute')->everyMinute()->sendOutputTo('command11_output.log');
        $schedule->command('finifty:every_minute')->everyMinute()->sendOutputTo('command12_output.log');
        $schedule->command('naturalgas:every_minute')->everyMinute()->sendOutputTo('command13_output.log');
        $schedule->command('nifty:every_minute')->everyMinute()->sendOutputTo('command14_output.log');
        $schedule->command('midcpnifty:every_minute')->everyMinute()->sendOutputTo('command15_output.log');

        // User Home Page
        $schedule->command('angel_top_gainer:every_day')->everyMinute()->sendOutputTo('command16_output.log');
        $schedule->command('angel_top_loser:every_day')->everyMinute()->sendOutputTo('command17_output.log');
        $schedule->command('angel_short_oi_buildup:every_day')->everyMinute()->sendOutputTo('command18_output.log');
        $schedule->command('angel_long_oi_buildup:every_day')->everyMinute()->sendOutputTo('command19_output.log');
        $schedule->command('angel_short_covering_buildup:every_day')->everyMinute()->sendOutputTo('command20_output.log');
        $schedule->command('angel_long_unwinding_buildup:every_day')->everyMinute()->sendOutputTo('command21_output.log');
        $schedule->command('angel_pcr:every_day')->everyMinute()->sendOutputTo('command23_output.log');



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
