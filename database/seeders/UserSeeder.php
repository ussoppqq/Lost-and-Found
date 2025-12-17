<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        $bogor = $companies->where('company_name', 'Kebun Raya Bogor')->first();
        $cibodas = $companies->where('company_name', 'Kebun Raya Cibodas')->first();
        $purwodadi = $companies->where('company_name', 'Kebun Raya Purwodadi')->first();
        $bali = $companies->where('company_name', 'Kebun Raya Bali')->first();

        $adminRole = Role::where('role_code', 'ADMIN')->first();
        $userRole = Role::where('role_code', 'USER')->first();
        $moderatorRole = Role::where('role_code', 'MODERATOR')->first();

        // 1 Admin - Kebun Raya Bogor
        User::updateOrCreate(
            ['email' => 'admin@kebunraya.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $bogor->company_id,
                'role_id' => $adminRole->role_id,
                'full_name' => 'Super Admin',
                'phone_number' => '628111111111',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'moderator1@kebunraya.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $bogor->company_id,
                'role_id' => $moderatorRole->role_id,
                'full_name' => 'Moderator Bogor',
                'phone_number' => '628222222222',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );

        // Moderator Cibodas
        User::updateOrCreate(
            ['email' => 'moderator2@kebunraya.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $cibodas->company_id,
                'role_id' => $moderatorRole->role_id,
                'full_name' => 'Moderator Cibodas',
                'phone_number' => '628333333333',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'moderator3@kebunraya.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $purwodadi->company_id,
                'role_id' => $moderatorRole->role_id,
                'full_name' => 'Moderator Purwodadi',
                'phone_number' => '628444444444',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );

        // Moderator Bali
        User::updateOrCreate(
            ['email' => 'moderator4@kebunraya.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $bali->company_id,
                'role_id' => $moderatorRole->role_id,
                'full_name' => 'Moderator Bali',
                'phone_number' => '628555555555',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );

        // User biasa untuk testing
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => $bogor->company_id,
                'role_id' => $userRole->role_id,
                'full_name' => 'User Test',
                'phone_number' => '628666666666',
                'password' => Hash::make('password'),
                'is_verified' => true,
            ]
        );
    }
}
