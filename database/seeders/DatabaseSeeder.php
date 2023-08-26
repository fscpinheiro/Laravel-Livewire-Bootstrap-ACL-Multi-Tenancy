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
            ClienteSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,

            AppSeeder::class,
            PermissionSeeder::class,

            RolePermissionSeeder::class,
            ClienteAppSeeder::class,
            MenuSeeder::class,

            RoadMapSeeder::class,

        ]);
    }
}
