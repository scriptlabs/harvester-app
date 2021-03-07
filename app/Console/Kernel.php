<?php

namespace App\Console;

use App\Console\Commands\SensorRead;
use App\Console\Commands\SocketToggle;

use App\Console\Commands\WebcamCapture;
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
        // webcam capture
        //CapturesCreate::class,
        //CapturesBackup::class,
        // sensor processing
        SensorRead::class,
        // switch processing
        SocketToggle::class,
        WebcamCapture::class,
        // database
        //DatabaseBackup::class,
        // broadcast
        //BroadcastSend::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
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
