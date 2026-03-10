<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivitiesSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('CSVfile/global_activities_50000 activities 1.csv');

        if (!file_exists($filePath)) {
            $this->command->error("CSV file not found: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        $batchSize = 1000;
        $activities = [];
        $hobbyCache = [];
        $now = Carbon::now();

        $ageOptions = ['16-17', '18-30', '30-45', '45+'];

        $this->command->info("Seeding activities...");

        while (($row = fgetcsv($file)) !== false) {

            $category = trim($row[8]);
            $title = $row[6];
            $description = $row[7];
            $difficulty = ucfirst(strtolower($row[9]));
            $tier = $row[10];
            $duration = $row[14];

           
            if (!isset($hobbyCache[$category])) {

                $hobby = DB::table('hobbies')
                    ->where('name', $category)
                    ->first();

                if ($hobby) {
                    $hobbyCache[$category] = $hobby->id;
                } else {
                    $id = DB::table('hobbies')->insertGetId([
                        'name' => $category,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

                    $hobbyCache[$category] = $id;
                }
            }

            $hobbyId = $hobbyCache[$category];


            if (!in_array($difficulty, ['Easy','Intermediate','Advanced'])) {
                $difficulty = 'Easy';
            }

            if (!in_array($tier, ['Tier 1','Tier 2','Tier 3'])) {
                $tier = 'Tier 1';
            }

            $activities[] = [
                'hobby_id' => $hobbyId,
                'title' => $title,
                'description' => $description,
                'duration_minutes' => $duration,
                'energy_level' => $difficulty,
                'age_suitability' => $ageOptions[array_rand($ageOptions)],
                'tier' => $tier,
                'neurodivergent_friendly' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];


            if (count($activities) >= $batchSize) {
                DB::table('activities')->insert($activities);
                $activities = [];
            }
        }

        if (!empty($activities)) {
            DB::table('activities')->insert($activities);
        }

        fclose($file);

        $this->command->info("Activities seeding completed!");
    }
}