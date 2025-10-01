<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Loan Disbursement Schedule - 3 times daily
        // Morning batch at 9:00 AM
        $schedule->command('disbursements:process --time=morning')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/disbursements-morning.log'))
            ->emailOutputOnFailure(config('disbursements.alert_email'))
            ->description('Morning disbursement batch (9:00 AM)');
        
        // Afternoon batch at 12:00 PM
        $schedule->command('disbursements:process --time=afternoon')
            ->dailyAt('12:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/disbursements-afternoon.log'))
            ->emailOutputOnFailure(config('disbursements.alert_email'))
            ->description('Afternoon disbursement batch (12:00 PM)');
        
        // Evening batch at 3:00 PM
        $schedule->command('disbursements:process --time=evening')
            ->dailyAt('15:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/disbursements-evening.log'))
            ->emailOutputOnFailure(config('disbursements.alert_email'))
            ->description('Evening disbursement batch (3:00 PM)');
        
        // Daily reconciliation at 11:00 PM
        $schedule->command('nmb:reconcile')
            ->dailyAt('23:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/nmb-reconciliation.log'))
            ->description('Daily NMB transaction reconciliation');
        
        // Retry failed disbursements every 2 hours
        $schedule->command('disbursements:retry-failed')
            ->everyTwoHours()
            ->between('08:00', '18:00')
            ->withoutOverlapping()
            ->description('Retry failed disbursements');
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
