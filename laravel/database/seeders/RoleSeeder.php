<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_code' => 'ADMIN', 'role_name' => 'Admin'],
            ['role_code' => 'MODERATOR', 'role_name' => 'Moderator'],
            ['role_code' => 'USER', 'role_name' => 'Regular User'],
        ];

        foreach ($roles as $role) {
            Role::create([
                'role_id'   => Str::uuid(),
                'role_code' => $role['role_code'],
                'role_name' => $role['role_name'],
            ]);
        }
    }
}
