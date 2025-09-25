<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create([
            'company_id'      => Str::uuid(),
            'company_name'    => 'Default Company',
            'company_address' => 'Bogor, Indonesia',
        ]);
    }
}
