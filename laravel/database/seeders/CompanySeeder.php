<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use Illuminate\Support\Str;

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
