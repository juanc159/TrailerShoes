<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GenderSeeder::class,
            CivilStatusSeeder::class,
            IdentityTypeSeeder::class,
            PermissionSeeder::class,
            PermissionSeederUpdate20230811::class,
            RoleSeeder::class,
            MenuSeeder::class,
            UserSeeder::class,
            RequirementStateSeeder::class,
            CalendarTypeSeeder::class,
            MenuSeederUpdate20230811::class,
            CompanySeeder::class,
        ]);
    }
}
