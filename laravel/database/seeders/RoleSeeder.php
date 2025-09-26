<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'role_id'   => Str::uuid(),
            'role_code' => 'ADMIN',
            'role_name' => 'Admin',
            
        ]);

        Role::create([
            'role_id'   => Str::uuid(),
            'role_code' => 'USER',
            'role_name' => 'User',
        ]);
        
        Role::create([
            'role_id'   => Str::uuid(),
            'role_code' => 'Moderator',
            'role_name' => 'moderator',
        ]);
    }
}
