<?php

use App\Models\Activity;
use App\Support\InstructionFormatter;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('activities:normalize-instructions', function () {
    $updated = 0;

    Activity::query()
        ->whereNotNull('instruction')
        ->chunkById(500, function ($activities) use (&$updated) {
            foreach ($activities as $activity) {
                $normalized = InstructionFormatter::normalize($activity->instruction);

                if ($normalized !== $activity->instruction) {
                    $activity->instruction = $normalized;
                    $activity->save();
                    $updated++;
                }
            }
        });

    $this->info("Normalized instructions for {$updated} activities.");
})->purpose('Normalize activities instruction text format and fix common key/format issues.');
