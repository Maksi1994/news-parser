<?php

namespace App\Console;

use App\Jobs\Parsers\Habrahabr\RunParcing;
use App\NewsSource;
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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        foreach (NewsSource::all() as $source) {
            switch ($source->name) {
                case 'Habrahabr':
                    $job = new RunParcing($source);
                    break;
            }

            if ($source->active_parse) {
                $schedule->job($job)->cron("*/{$source->parse_interval} * * * *");
            }
        }

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
