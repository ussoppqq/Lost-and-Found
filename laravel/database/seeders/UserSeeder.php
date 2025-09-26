<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $company = \App\Models\Company::first();
$adminRole = \App\Models\Role::where('role_name', 'Admin')->first();
$userRole  = \App\Models\Role::where('role_name', 'User')->first();
$moderatorRole = \App\Models\Role::where('role_name', 'Moderator')->first();

// Admin
User::updateOrCreate(
    ['email' => 'admin@example.com'],
    [
        'company_id'   => $company->company_id,
        'role_id'      => $adminRole->role_id,
        'full_name'    => 'Super Admin',
        'phone_number' => '628111111111',
        'password'     => Hash::make('password'),
        'is_verified'  => true,
    ]
);

// User biasa
User::updateOrCreate(
    ['email' => 'johndoe@example.com'],
    [
        'company_id'   => $company->company_id,
        'role_id'      => $userRole->role_id,
        'full_name'    => 'Jhon Doe',
        'phone_number' => '628222222222',
        'password'     => Hash::make('password'),
        'is_verified'  => true,
    ]
);

// Moderator
User::updateOrCreate(
    ['email' => 'moderator@example.com'],
    [
        'company_id'   => $company->company_id,
        'role_id'      => $moderatorRole->role_id,
        'full_name'    => 'Moderator User',
        'phone_number' => '628333333333',
        'password'     => Hash::make('password'),
        'is_verified'  => true,
    ]
);
    }
}