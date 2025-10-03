<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $adminRole = Role::where('role_name', 'Admin')->first();
        $userRole = Role::where('role_name', 'User')->first();
        $moderatorRole = Role::where('role_name', 'Moderator')->first();

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $company->company_id,
                'role_id' => $adminRole->role_id,
                'full_name' => 'Super Admin',
                'phone_number' => '628111111111',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );

        // User biasa
        User::updateOrCreate(
            ['email' => 'johndoe@example.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $company->company_id,
                'role_id' => $userRole->role_id,
                'full_name' => 'John Doe',
                'phone_number' => '628222222222',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );

        // Moderator
        User::updateOrCreate(
            ['email' => 'moderator@example.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $company->company_id,
                'role_id' => $moderatorRole->role_id,
                'full_name' => 'Moderator User',
                'phone_number' => '628333333333',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );

        // Tambahan user
        User::updateOrCreate(
            ['email' => 'budi@example.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $company->company_id,
                'role_id' => $userRole->role_id,
                'full_name' => 'Budi Santoso',
                'phone_number' => '081234567892',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti@example.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $company->company_id,
                'role_id' => $userRole->role_id,
                'full_name' => 'Siti Nurhaliza',
                'phone_number' => '081234567893',
                'password' => Hash::make('password'),
                'is_verified' => false,
            ]
        );
    }
}
