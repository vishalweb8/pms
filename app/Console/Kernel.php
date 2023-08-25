<?php

namespace App\Console;

use App\Helpers\Helper;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Request;

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
        // $schedule->command('inspire')->hourly();

        // For allocate leave on 1st April of every year
        $schedule->command('financialyear:leaveallocation')->yearlyOn(4, 1, '01:00');
        $schedule->command('calculate:revenue')->monthlyOn(1, '12:00');

        $schedule->call(function (){
            $today = now();
            info("Update pending sod entry for previous day Function call ");
            $dashboardController = new DashboardController();
            $request = new Request();
            $request['date'] = $today->copy()->subDay()->format('Y-m-d');
            $dashboardController->updatePendingEntries($request);

            $request['date'] = $today->format('Y-m-d');
            $dashboardController->updatePendingEntries($request);

        })->dailyAt("00:01");

        $schedule->call(function (){
            $today = now();
            info("Add today's pending sod entry Function call ");
            $dashboardController = new DashboardController();
            $request = new Request();

            $request['date'] = $today->format('Y-m-d');
            $dashboardController->updatePendingEntries($request);

        })->dailyAt("00:10");

        if(env('APP_ENV') == 'production') {
            $schedule->call(function (){
                $request = new Request;
                $request['date'] = 'today';
                $request['return_data_as'] = 'email_content';
                $request['run_at'] = now();
                $request['send_to'] = ['harpalsinh.inexture@gmail.com']; // List of emails on which want to received the mail
                $automationController = new AutomationController;
                $automationController->sendTodaysDefaulterInEmail($request);

            })->weekdays()->dailyAt("12:15");
        }
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
