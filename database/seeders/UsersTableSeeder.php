<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('users')->insert ([
        ['name' => 'Admin', 'email' => 'admin@gmail.com', 'password' => bcrypt('password'), 'provider' => 'email', 'provider_id' => null, 'created_at'=> now(), 'updated_at' => now()]
       ]);
    }
}
