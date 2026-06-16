<?php

namespace App\Logging;

use App\Models\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DatabaseHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        Log::create([
            'level'   => $record->level->getName(),  // Monolog v3
            // 'level' => $record['level_name'],     // Monolog v2 (Laravel 9 and below)
            'message' => $record->message,
            'context' => $record->context,
            'extra'   => $record->extra,
            'channel' => $record->channel,
        ]);
    }
}
