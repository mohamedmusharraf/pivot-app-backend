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

        $header = fgetcsv($file);

        $countryCache = [];
        $regions = [];
        $now = Carbon::now();

        $this->command->info("Starting to seed countries and regions...");

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 4) continue;

            $regionName  = trim($row[1]); 
            $countryName = trim($row[2]); 
            $cityName    = trim($row[3]); 

            if (!$countryName || !$regionName || !$cityName) {
                continue;
            }

            if (!isset($countryCache[$countryName])) {

                $country = DB::table('countries')
                    ->where('name', $countryName)
                    ->first();

                if ($country) {
                    $countryCache[$countryName] = $country->id;
                } else {
                    
                    $generatedIso = strtoupper(substr($countryName, 0, 2));

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

            $regions[] = [
                'country_id' => $countryId,
                'region'     => $regionName,
                'city'       => $cityName,
                'created_at' => $now,
                'updated_at' => $now
            ];

            if (count($regions) >= 1000) {
                DB::table('regions')->insert($regions);
                $regions = [];
            }
        }

        fclose($file);

        if (!empty($regions)) {
            DB::table('regions')->insert($regions);
        }

        $this->command->info("Countries and Regions seeded successfully.");
    }
}