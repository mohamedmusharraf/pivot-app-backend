<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegionTableSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('CSVfile/global_activities_50000 activities 1.csv');

        if (!file_exists($filePath)) {
            $this->command->error("CSV file not found at: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        // Skip header
        $header = fgetcsv($file);

        $countryCache = [];
        $regions = [];
        $now = Carbon::now();

        $this->command->info("Starting to seed countries and regions...");

        while (($row = fgetcsv($file)) !== false) {
            // Ensure the row has enough columns to avoid "Undefined offset" errors
            if (count($row) < 4) continue;

            $regionName  = trim($row[1]); // Region column
            $countryName = trim($row[2]); // Country column
            $cityName    = trim($row[3]); // City column

            if (!$countryName || !$regionName || !$cityName) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Insert Country (if not exists)
            |--------------------------------------------------------------------------
            */
            if (!isset($countryCache[$countryName])) {

                $country = DB::table('countries')
                    ->where('name', $countryName)
                    ->first();

                if ($country) {
                    $countryCache[$countryName] = $country->id;
                } else {
                    // AUTO-GENERATE ISO CODE: 
                    // Take first two letters, uppercase them. 
                    // This ensures it returns a 'string' type, not an object.
                    $generatedIso = strtoupper(substr($countryName, 0, 2));

                    // Safety check: if name is shorter than 2 chars, fallback to 'XX'
                    if (strlen($generatedIso) < 2) {
                        $generatedIso = 'XX';
                    }

                    $countryId = DB::table('countries')->insertGetId([
                        'iso_code'       => $generatedIso,
                        'name'           => $countryName,
                        'default_locale' => 'en',
                        'currency_code'  => 'USD',
                        'created_at'     => $now,
                        'updated_at'     => $now
                    ]);

                    $countryCache[$countryName] = $countryId;
                }
            }

            $countryId = $countryCache[$countryName];

            /*
            |--------------------------------------------------------------------------
            | Prepare Region Data for Batch Insert
            |--------------------------------------------------------------------------
            */
            $regions[] = [
                'country_id' => $countryId,
                'region'     => $regionName,
                'city'       => $cityName,
                'created_at' => $now,
                'updated_at' => $now
            ];

            // Every 1000 rows, insert into DB and clear array to save memory
            if (count($regions) >= 1000) {
                DB::table('regions')->insert($regions);
                $regions = [];
            }
        }

        fclose($file);

        // Insert any remaining regions in the array
        if (!empty($regions)) {
            DB::table('regions')->insert($regions);
        }

        $this->command->info("Countries and Regions seeded successfully.");
    }
}