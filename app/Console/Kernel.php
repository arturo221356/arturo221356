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
use App\Jobs\ProcessElektra2JamayLogin;
use App\Jobs\ProcessElektra2JamayLogout;
use App\Jobs\ProcessCoppelLogin;
use App\Jobs\ProcessCoppelLogout;

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

       // $schedule->command('exportados:daily')->timezone('America/Mexico_City')->dailyAt('07:00');

       // $schedule->command('queue:retry all')->timezone('America/Mexico_City')->dailyAt('14:30');

        $schedule->command('delete:oldlineas')->timezone('America/Mexico_City')->dailyAt('23:00');

       // $schedule->command('revisa:portas')->timezone('America/Mexico_City')->dailyAt('04:00');

        $schedule->command('delete:invoices')->timezone('America/Mexico_City')->monthlyOn(4, '15:00')->emailOutputTo('arturo221357@gmail.com');

        //$schedule->command('telcel:login')->timezone('America/Mexico_City')->dailyAt('06:00')->emailOutputTo('arturo221357@gmail.com');



        /*

        procesa las entradas y salidas de los usuarios de cadenas

        */

        /////MAVI

       // $schedule->call(function () {
            // $job = new ProcessMaviLogin;
            // $job->delay(rand(0, 45));
            // dispatch($job);

           // ProcessMaviLogin::dispatch()->delay(now()->addMinutes(rand(0, 45)));
       // })->timezone('America/Mexico_City')->dailyAt("09:45");



       // $schedule->call(function () {
            // $job = new ProcessMaviLogOut;
            // $job->delay(rand(0, 45));
            // dispatch($job);

         //   ProcessMaviLogOut::dispatch()->delay(now()->addMinutes(rand(0, 45)));
       // })->timezone('America/Mexico_City')->dailyAt("19:15");



        ////TELMEX


       // $schedule->call(function () {
         //   ProcessTelmexLogin::dispatch()->delay(now()->addMinutes(rand(0, 45)));
       // })->timezone('America/Mexico_City')->weekdays()->at("09:00");


        //$schedule->call(function () {
           // ProcessTelmexLogOut::dispatch()->delay(now()->addMinutes(rand(0, 45)));
        //})->timezone('America/Mexico_City')->weekdays()->at("16:15");


        //// ELEKTRA

        //$schedule->call(function () {
            //ProcessElektraJLogin::dispatch()->delay(now()->addMinutes(rand(0, 15)));
        //})->timezone('America/Mexico_City')->dailyAt("09:00");

        //$schedule->call(function () {
            //ProcessElektraJLogOut::dispatch()->delay(now()->addMinutes(rand(0, 15)));
       // })->timezone('America/Mexico_City')->dailyAt("17:00");


        /// elektra 2
        //$schedule->call(function () {
           // ProcessElektra2JamayLogin::dispatch()->delay(now()->addMinutes(rand(0, 15)));
        //})->timezone('America/Mexico_City')->dailyAt("16:00");

        //$schedule->call(function () {
           // ProcessElektra2JamayLogout::dispatch()->delay(now()->addMinutes(rand(0, 15)));
        //})->timezone('America/Mexico_City')->dailyAt("20:00");


        ///coppel

        //$schedule->call(function () {
          //  ProcessCoppelLogin::dispatch()->delay(now()->addMinutes(rand(0, 15)));
        //})->timezone('America/Mexico_City')->dailyAt("10:00");

        //$schedule->call(function () {
          //  ProcessCoppelLogout::dispatch()->delay(now()->addMinutes(rand(0, 15)));
        //})->timezone('America/Mexico_City')->dailyAt("15:00");
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
