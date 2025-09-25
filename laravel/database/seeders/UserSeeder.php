<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
{
    $companyId = \App\Models\Company::first()->company_id;
    $roleId = \App\Models\Role::where('role_name', 'Admin')->first()->role_id;

    User::create([
        'company_id'   => $companyId,
        'role_id'      => $roleId,
        'full_name'    => 'Test User',
        'email'        => 'test@example.com',
        'phone_number' => '6281234567890',
        'password'     => Hash::make('password'),
        'is_verified'  => true,
    ]);
}
}
