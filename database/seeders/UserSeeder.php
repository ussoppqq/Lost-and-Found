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
        $adminRole = Role::where('role_code', 'ADMIN')->first();
        $userRole = Role::where('role_code', 'USER')->first();
        $moderatorRole = Role::where('role_code', 'MODERATOR')->first();

        if (!$adminRole || !$moderatorRole || !$userRole) {
            $this->command->error('Roles not found. Please run RoleSeeder first.');
            return;
        }

        if ($companies->isEmpty()) {
            $this->command->error('No companies found. Please run CompanySeeder first.');
            return;
        }

        // Mapping company names to short codes
        $companyMapping = [
            'Kebun Raya Bogor' => 'bogor',
            'Kebun Raya Cibodas' => 'cibodas',
            'Kebun Raya Purwodadi' => 'purwodadi',
            'Kebun Raya Bali' => 'bali',
        ];

        $counter = 1;

        // Create Admin and Moderator for each company
        foreach ($companies as $company) {
            $shortName = $companyMapping[$company->company_name] ?? strtolower(str_replace(' ', '', $company->company_name));

            // Create Admin
            $adminEmail = "admin@{$shortName}.com";
            $adminPhone = '62812' . str_pad($counter, 8, '0', STR_PAD_LEFT);

            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'user_id' => Str::uuid(),
                    'company_id' => $company->company_id,
                    'role_id' => $adminRole->role_id,
                    'full_name' => 'Admin ' . $company->company_name,
                    'phone_number' => $adminPhone,
                    'password' => Hash::make('password123'),
                    'is_verified' => true,
                    'phone_verified_at' => now(),
                    'email_verified_at' => now(),
                ]
            );

            $this->command->info("✓ Created Admin for {$company->company_name}");
            $this->command->info("  Email: {$adminEmail} | Phone: {$adminPhone} | Password: password123");

            $counter++;

            // Create Moderator
            $moderatorEmail = "moderator@{$shortName}.com";
            $moderatorPhone = '62813' . str_pad($counter, 8, '0', STR_PAD_LEFT);

            User::updateOrCreate(
                ['email' => $moderatorEmail],
                [
                    'user_id' => Str::uuid(),
                    'company_id' => $company->company_id,
                    'role_id' => $moderatorRole->role_id,
                    'full_name' => 'Moderator ' . $company->company_name,
                    'phone_number' => $moderatorPhone,
                    'password' => Hash::make('password123'),
                    'is_verified' => true,
                    'phone_verified_at' => now(),
                    'email_verified_at' => now(),
                ]
            );

            $this->command->info("✓ Created Moderator for {$company->company_name}");
            $this->command->info("  Email: {$moderatorEmail} | Phone: {$moderatorPhone} | Password: password123");
            $this->command->info("---");

            $counter++;
        }

        // User biasa untuk testing (tidak punya company_id)
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'user_id' => Str::uuid(),
                'company_id' => null, // Regular user tidak punya company_id
                'role_id' => $userRole->role_id,
                'full_name' => 'User Test',
                'phone_number' => '628999999999',
                'password' => Hash::make('password123'),
                'is_verified' => true,
                'phone_verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("✓ Created regular user for testing");
        $this->command->info("  Email: user@example.com | Phone: 628999999999 | Password: password123");
        $this->command->info("---");
        $this->command->info("✅ Successfully created " . ($companies->count() * 2 + 1) . " users");
    }
}
