<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivitiesSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('CSVfile/Aneesa - 02APRIL26 -TC.csv');

        if (!file_exists($filePath)) {
            $this->command->error("CSV file not found: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        if (!$file) {
            $this->command->error("Unable to open the file.");
            return;
        }

        $headers = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            if (count($headers) !== count($row)) {
                continue;
            }

            $data = array_combine($headers, $row);

            $hobby = DB::table('hobbies')->where('name', $data['category'])->first();

            if (!$hobby) {
                $hobbyId = DB::table('hobbies')->insertGetId([
                    'name' => $data['category'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $hobbyId = $hobby->id;
            }


            [$minAge, $maxAge] = $this->extractAgeRange($data['age_range'] ?? null);
            $durationMinutes = $this->convertToMinutes($data['duration'] ?? null);

            $moods = $this->parseMoodMatch($data['mood_match'] ?? null);

            DB::table('activities')->insert([
                'hobby_id' => $hobbyId,
                'activity_title' => $data['activity_title'] ?? '',
                'instruction' => $data['instruction'] ?? '',
                'activity_type' => $data['activity_type'] ?? null,
                'subcategory' => $data['subcategory'] ?? null,

                'duration_minutes' => $durationMinutes,

                'min_age' => $minAge,
                'max_age' => $maxAge,

                'tier' => $this->extractTierNumber($data['difficulty'] ?? null),
                'cost' => $data['cost'] ?? null,
                'location' => $data['location'] ?? null,

                'neurodivergent_friendly' => isset($data['neurodivergent_friendly'])
                    ? filter_var($data['neurodivergent_friendly'], FILTER_VALIDATE_BOOLEAN)
                    : false,

                'neurodivergent_notes' => $data['neurodivergent_notes'] ?? null,
                'sensory_tags' => $data['sensory_tags'] ?? null,
                'social_type' => $data['social_type'] ?? null,
                'energy_level' => $data['energy_level'] ?? 'Easy',
                'outcome_tag' => $data['outcome_tag'] ?? null,

                'mood_match' => json_encode($moods),

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        fclose($file);

        $this->command->info("Activities seeding completed successfully!");
    }

    private function extractTierNumber($value)
    {
        if (!$value) return 1;

        preg_match('/\d+/', $value, $matches);
        return isset($matches[0]) ? (int)$matches[0] : 1;
    }

    private function extractAgeRange($ageRange)
    {
        if (!$ageRange) return [null, null];

        $ageRange = strtolower(trim($ageRange));

        if (preg_match('/(\d+)\s*-\s*(\d+)/', $ageRange, $m)) {
            return [(int)$m[1], (int)$m[2]];
        }

        if (preg_match('/(\d+)\s*to\s*(\d+)/', $ageRange, $m)) {
            return [(int)$m[1], (int)$m[2]];
        }

        if (preg_match('/(\d+)\+/', $ageRange, $m)) {
            return [(int)$m[1], null];
        }

        if (is_numeric($ageRange)) {
            return [(int)$ageRange, (int)$ageRange];
        }

        return [null, null];
    }

    private function convertToMinutes($duration)
    {
        if (!$duration) return 0;

        $duration = strtolower(trim($duration));

        if (preg_match('/([\d.]+)\s*(hour|hr|hrs)/', $duration, $m)) {
            return (int) round($m[1] * 60);
        }

        if (preg_match('/(\d+)\s*(min|mins|minutes)/', $duration, $m)) {
            return (int)$m[1];
        }

        if (is_numeric($duration)) {
            return (int)$duration;
        }

        return 0;
    }


    private function parseMoodMatch($value)
    {
        if (!$value) return [];

        $value = str_replace(['[', ']'], '', $value);

        $value = str_replace(["'", '"'], '', $value);

        $moods = explode(',', $value);

        return array_values(array_filter(array_map(function ($mood) {
            return trim($mood);
        }, $moods)));
    }
}