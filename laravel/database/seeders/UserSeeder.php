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
        $company = Company::first();
        $adminRole = Role::where('role_name', 'Admin')->first();
        $userRole  = Role::where('role_name', 'User')->first();

        if (!$company || !$adminRole || !$userRole) {
            $this->command->error('❌ Pastikan Company, Role Admin & Role User sudah ada sebelum seeding User!');
            return;
        }

        // Buat admin
        User::create([
            'company_id'   => $company->company_id,
            'role_id'      => $adminRole->role_id,
            'full_name'    => 'Super Admin',
            'email'        => 'admin@example.com',
            'phone_number' => '628111111111',
            'password'     => Hash::make('password'), // password default
            'is_verified'  => true,
        ]);

        // Buat user biasa
        User::create([
            'company_id'   => $company->company_id,
            'role_id'      => $userRole->role_id,
            'full_name'    => 'Jhon Doe',
            'email'        => 'johndoe@example.com',
            'phone_number' => '628222222222',
            'password'     => Hash::make('password'),
            'is_verified'  => true,
        ]);
    }
}
