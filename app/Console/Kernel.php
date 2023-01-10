<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ProcessMaviLogin;
use App\Jobs\ProcessTelmexLogin;
use App\Jobs\ProcessMaviLogOut;
use App\Jobs\ProcessTelmexLogOut;
use App\Jobs\ProcessElektraJLogin;
use App\Jobs\ProcessElektraJLogOut;

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
        // $schedule->command('inspire')->everyTwoMinutes()->emailOutputTo('arturo221357@gmail.com');

        $schedule->command('exportados:daily')->timezone('America/Mexico_City')->dailyAt('07:00');

        $schedule->command('queue:retry all')->timezone('America/Mexico_City')->dailyAt('14:30');

        $schedule->command('delete:oldlineas')->timezone('America/Mexico_City')->dailyAt('23:00');

        $schedule->command('revisa:portas')->timezone('America/Mexico_City')->dailyAt('04:00');

        $schedule->command('delete:invoices')->timezone('America/Mexico_City')->monthlyOn(4, '15:00')->emailOutputTo('arturo221357@gmail.com');

        $schedule->command('telcel:login')->timezone('America/Mexico_City')->dailyAt('06:00')->emailOutputTo('arturo221357@gmail.com');



        /*

        procesa las entradas y salidas de los usuarios de cadenas

        */

        /////MAVI

        $schedule->call(function () {
            $job = new ProcessMaviLogin;
            $job->delay(rand(0, 45));
            dispatch($job);
        })->dailyAt("09:45");



        $schedule->call(function () {
            $job = new ProcessMaviLogOut;
            $job->delay(rand(0, 45));
            dispatch($job);
        })->dailyAt("19:15");



        ////TELMEX


        $schedule->call(function () {
            $job = new ProcessTelmexLogin;
            $job->delay(rand(0, 45));
            dispatch($job);
        })->weekdays()->at("09:00");


        $schedule->call(function () {
            $job = new ProcessTelmexLogOut;
            $job->delay(rand(0, 45));
            dispatch($job);
        })->weekdays()->at("16:15");


        //// ELEKTRA

        $schedule->call(function () {
            $job = new ProcessElektraJLogin;
            $job->delay(rand(0, 45));
            dispatch($job);
        })->dailyAt("19:15");

        $schedule->call(function () {
            $job = new ProcessElektraJLogOut;
            $job->delay(rand(0, 45));
            dispatch($job);
        })->dailyAt("19:15");
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
