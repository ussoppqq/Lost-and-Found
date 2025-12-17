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
            CompanySeeder::class,
            RoleSeeder::class,
            UserSeeder::class, // Include admin & moderator for each company
            CategorySeeder::class,
            PostSeeder::class,
            LocationSeeder::class,
            ReportSeeder::class, // Add sample reports for each company
        ]);
    }

}
