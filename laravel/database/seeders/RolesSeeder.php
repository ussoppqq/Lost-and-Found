<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Str;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['role_id' => (string) Str::uuid(), 'role_code' => 'USER', 'role_name' => 'User'],
            ['role_id' => (string) Str::uuid(), 'role_code' => 'MODERATOR', 'role_name' => 'Moderator'],
            ['role_id' => (string) Str::uuid(), 'role_code' => 'ADMIN', 'role_name' => 'Admin'],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(['role_code' => $r['role_code']], $r);
        }
    }
}
