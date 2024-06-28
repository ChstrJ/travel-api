<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Travel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        RoleSeeder::class;
        Travel::factory(10)->create();        
        Tour::factory(10)->create();        
    }
}
