<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BetaTestersSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('CSVfile/Pivot Beta Testers Details.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        $spreadsheet = IOFactory::load($filePath);

        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray();

        $data = [];

        foreach ($rows as $index => $row) {

            // Skip heading row
            if ($index === 0) {
                continue;
            }

            // Skip empty rows
            if (empty($row[0]) || empty($row[1])) {
                continue;
            }

            $data[] = [
                'name' => trim($row[0]),
                'email' => trim($row[1]),
                'password' => Hash::make(trim($row[3] ?? 'password123')),
                'provider' => null,
                'provider_id' => null,
                'last_login_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($data);

        $this->command->info('Beta testers seeded successfully!');
    }
}