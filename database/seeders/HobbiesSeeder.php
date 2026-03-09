<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HobbiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('hobbies')->insert ([
        ['name' => 'Physical Fitness', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Creative Arts', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Nature & Outdoors', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Culinary Arts', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Mindfulness & Wellness', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Fiber Arts', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Writing & Journaling', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Gardening & Botany', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Analog Gaming', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Woodworking', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Puzzles & Logic', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'DIY & Home Repair', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Music & Sound', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Animal Connection', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Collecting', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Earth Sciences', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Heritage & History', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Mechanical Hobbies','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Personal Style','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Community Service','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Social Connection','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Paper Crafts','icon_url' => null, 'created_at' => now(),'updated_at' => now()],
        ['name' => 'Adventure Skills','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Philosophy','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Language Learning','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Analog Photography', 'icon_url' => null,'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Local Exploration', 'icon_url' => null, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Jewelry & Metal','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Spiritual Rituals','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Passive Relaxation','icon_url' => null,'created_at' => now(),'updated_at' => now()],
        ['name' => 'Navigation & Direction', 'icon_url' => null,'created_at' => now(),'updated_at' => now()],
       ]);
    }
}
