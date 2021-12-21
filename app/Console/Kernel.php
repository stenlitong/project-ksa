<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\DailyReportItemBelowStockJob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   
        $schedule->job(new DailyReportItemBelowStockJob)->dailyAt('10:00')->onOneServer()->runInBackground();
        // $schedule->job(new SendItemBelowStockReportJob('Jakarta'))->fridays()->at('10:00')->onOneServer()->runInBackground();
        // $schedule->job(new SendItemBelowStockReportJob('Banjarmasin'))->fridays()->at('10:00')->onOneServer()->runInBackground();
        // $schedule->job(new SendItemBelowStockReportJob('Samarinda'))->fridays()->at('10:00')->onOneServer()->runInBackground();
        // $schedule->job(new SendItemBelowStockReportJob('Bunati'))->fridays()->at('10:00')->onOneServer()->runInBackground();
        // $schedule->job(new SendItemBelowStockReportJob('Babelan'))->fridays()->at('10:00')->onOneServer()->runInBackground();
        // $schedule->job(new SendItemBelowStockReportJob('Berau'))->fridays()->at('10:00')->onOneServer()->runInBackground();
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
