<?php

namespace App\Console\Commands;

use App\Models\Log;
use Illuminate\Console\Command;

class ImportLogs extends Command
{
    protected $signature = 'logs:import {--file=laravel.log}';
    protected $description = 'Import existing log file into database';

    public function handle()
    {
        $file = storage_path('logs/' . $this->option('file'));

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return 1;
        }

        $pattern = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+?)(\{.*\})?(\s*\[.*\])?$/m';

        $content = file_get_contents($file);
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $bar = $this->output->createProgressBar(count($matches));

        foreach ($matches as $match) {
            Log::create([
                'level'   => strtolower($match[3]),
                'message' => trim($match[4]),
                'channel' => $match[2],
                'context' => isset($match[5]) ? json_decode($match[5], true) : null,
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nImported " . count($matches) . " log entries.");
        return 0;
    }
}
