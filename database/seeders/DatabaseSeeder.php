<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            TierTableSeeder::class,
            // RegionTableSeeder::class,
            ActivitiesSeeder::class,
            // HobbiesSeeder::class,
            CountriesTableSeeder::class,
        ]);
    }
}
