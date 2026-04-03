<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartScheduler extends Command
{
    protected $signature = 'scheduler:start';
    protected $description = 'Start the Laravel scheduler as a background process';

    public function handle(): int
    {
        $this->info('Starting Laravel Scheduler...');
        $this->info('Market data will be fetched automatically every minute.');
        $this->info('Press Ctrl+C to stop.');
        $this->newLine();

        exec('start /B php artisan schedule:work > nul 2>&1');

        $this->info('Scheduler started in background!');
        return Command::SUCCESS;
    }
}
