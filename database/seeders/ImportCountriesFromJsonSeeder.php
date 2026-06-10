<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Country;

class ImportCountriesFromJsonSeeder extends Seeder
{
    public function run(): void
    {
        $path = $this->command->ask(
            'Path to countries JSON file',
            base_path('CSVfile/data.json')
        );

        if (!File::exists($path)) {
            $this->command->error("File not found: {$path}");
            return;
        }

        $items = json_decode(File::get($path), true);

        if (!is_array($items)) {
            $this->command->error('Invalid JSON structure.');
            return;
        }

        $updated = 0;
        $notFound = 0;

        foreach ($items as $isoCode => $item) {

            $isoCode = strtoupper(trim($isoCode));

            $country = Country::where('iso_code', $isoCode)->first();

            if (!$country) {
                $notFound++;
                $this->command->warn("Country not found: {$isoCode}");
                continue;
            }

            $country->update([
                'police' => !empty($item['police'])
                    ? implode(', ', $item['police'])
                    : null,

                'ambulance' => !empty($item['ambulance'])
                    ? implode(', ', $item['ambulance'])
                    : null,

                'fire' => !empty($item['fire'])
                    ? implode(', ', $item['fire'])
                    : null,

                'notes' => $item['notes'] ?? null,
            ]);

            $updated++;
        }

        $this->command->info("Countries updated: {$updated}");
        $this->command->info("Countries not found: {$notFound}");
    }
}