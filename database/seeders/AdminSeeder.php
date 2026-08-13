<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'pivot@gmail.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password123'),
                'status'   => 'active',
            ]
        );
    }
}