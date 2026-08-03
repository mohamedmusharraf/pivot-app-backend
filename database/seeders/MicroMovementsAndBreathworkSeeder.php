<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MicroMovementsAndBreathworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = base_path('CSVfile/Micro_Movements_and_Breathwork_500 (1).csv');

        if (!file_exists($filePath)) {
            $this->command->error("CSV file not found at path: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        $rawHeader = fgetcsv($file);
        if (!$rawHeader) {
            $this->command->error("CSV file is empty.");
            fclose($file);
            return;
        }

        $header = array_map(function ($col) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x7F\xEF\xBB\xBF]/', '', $col)));
        }, $rawHeader);

        $totalInserted = 0;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($file)) !== false) {
                if (empty(array_filter($row))) {
                    continue;
                }

                if (count($header) !== count($row)) {
                    continue;
                }

                $data = array_combine($header, $row);

                $title = trim($data['activity_title'] ?? '');
                if (empty($title) || $title === '?') {
                    continue;
                }

                $rawNeuro = strtolower(trim($data['neurodivergent_friendly'] ?? ''));
                if (in_array($rawNeuro, ['1', 'true', 'yes', 'y'], true)) {
                    $neuroFriendly = 'Yes';
                } elseif (in_array($rawNeuro, ['partial', 'p'], true)) {
                    $neuroFriendly = 'Partial';
                } else {
                    $neuroFriendly = 'No';
                }

                $rawTier = trim($data['tier'] ?? '1');
                $tierInt = (int) floatval($rawTier);
                $tier = in_array((string)$tierInt, ['1', '2', '3'], true) ? (string)$tierInt : '1';

                $rawEnergy = ucfirst(strtolower(trim($data['energy_level'] ?? 'Low')));
                $energyLevel = in_array($rawEnergy, ['Low', 'Medium', 'High'], true) ? $rawEnergy : 'Low';

                $minAge = isset($data['min_age']) && is_numeric(trim($data['min_age']))
                    ? (int) trim($data['min_age'])
                    : null;

                $maxAge = isset($data['max_age']) && is_numeric(trim($data['max_age']))
                    ? (int) trim($data['max_age'])
                    : null;

                $moodMatch = !empty($data['mood_match'])
                    ? array_map('trim', explode(',', $data['mood_match']))
                    : [];

                Activity::create([
                    'hobby_id'                => 3, 
                    'activity_title'          => $title,
                    'description'             => $data['description'] ?? null,
                    'time'                    => $data['time'] ?? null,
                    'instruction'             => $data['instruction'] ?? null,
                    'activity_type'           => $data['activity_type'] ?? null,
                    'subcategory'             => $data['subcategory'] ?? null,
                    'duration_minutes'        => (string) (isset($data['duration_minutes']) ? (int) $data['duration_minutes'] : 5),
                    'tier'                    => $tier,            
                    'cost'                    => $data['cost'] ?? 'Free',
                    'location'                => $data['location'] ?? null,
                    'age_range'               => $data['age_range'] ?? null,
                    'min_age'                 => $minAge,
                    'max_age'                 => $maxAge,
                    'neurodivergent_friendly' => $neuroFriendly,  
                    'neurodivergent_notes'    => $data['neurodivergent_notes'] ?? null,
                    'sensory_tags'            => $data['sensory_tags'] ?? null,
                    'social_type'             => $data['social_type'] ?? null,
                    'energy_level'            => $energyLevel,     
                    'outcome_tag'             => $data['outcome_tag'] ?? null,
                    'mood_match'              => $moodMatch,
                    'status'                  => 'active',
                ]);

                $totalInserted++;
            }

            DB::commit();
            fclose($file);

            $this->command->info("Successfully inserted {$totalInserted} records into activities table!");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($file);
            Log::error('Seeder Error: ' . $e->getMessage());
            $this->command->error('Seeding error: ' . $e->getMessage());
        }
    }
}