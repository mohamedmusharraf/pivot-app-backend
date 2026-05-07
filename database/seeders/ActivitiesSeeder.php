<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class ActivitiesSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('CSVfile/Final 10k.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $headers = array_map('trim', $rows[0]);
        unset($rows[0]);

        foreach ($rows as $row) {

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

            $moods = $this->parseMoodMatch($data['mood_match'] ?? null);

            DB::table('activities')->insert([
                'hobby_id' => $hobbyId,
                'activity_title' => $data['activity_title'] ?? '',
                'description' => $data['description'] ?? null,
                'instruction' => $data['instruction'] ?? '',
                'activity_type' => $data['activity_type'] ?? null,
                'subcategory' => $data['subcategory'] ?? null,
                'duration_minutes' => isset($data['duration_minutes']) 
                    ? (int)$data['duration_minutes'] 
                    : 0,
                'time' => $data['time'] ?? null,
                'min_age' => isset($data['min_age']) ? (int)$data['min_age'] : null,
                'max_age' => isset($data['max_age']) ? (int)$data['max_age'] : null,
                'tier' => isset($data['tier']) ? (int)$data['tier'] : 1,
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
        $this->command->info("Activities seeding completed successfully!");
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