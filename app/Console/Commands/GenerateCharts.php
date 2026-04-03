<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateCharts extends Command
{
    protected $signature = 'generate:charts';
    protected $description = 'Generate chart images for sales and user data using Python';

    public function handle(): int
    {
        $this->info('Generating charts using Python...');

        $pythonScript = base_path('generate_charts.py');
        $command = "python \"{$pythonScript}\"";

        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            $this->info('Charts generated successfully!');
            foreach ($output as $line) {
                $this->line($line);
            }
            return Command::SUCCESS;
        } else {
            $this->error('Failed to generate charts. Make sure Python and required packages are installed.');
            $this->line('Run: pip install -r requirements.txt');
            return Command::FAILURE;
        }
    }
}
