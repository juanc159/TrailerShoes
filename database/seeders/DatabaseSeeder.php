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
            RoleSeeder::class,
            MenuSeeder::class,
            UserSeeder::class,
            CompanySeeder::class,
            AreaSeeder::class,
            ChargeSeeder::class,
            // EmployeeSeeder::class,
        ]);
    }
}
