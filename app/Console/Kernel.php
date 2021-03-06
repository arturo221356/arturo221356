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
                

        $schedule->command('exportados:daily')->timezone('America/Mexico_City')->dailyAt('07:00')->emailOutputTo('arturo221357@gmail.com');

        $schedule->command('revisa:portas')->timezone('America/Mexico_City')->dailyAt('04:00');

        $schedule->command('delete:invoices')->timezone('America/Mexico_City')->monthlyOn(4, '15:00')->emailOutputTo('arturo221357@gmail.com');

       
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
