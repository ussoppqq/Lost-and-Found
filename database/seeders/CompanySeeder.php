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
            'company_id' => Str::uuid(),
            'company_name' => 'Kebun Raya Bogor',
            'company_address' => 'Jl. Ir. H. Juanda No. 13, Bogor',
        ]);

        Company::create([
            'company_id' => Str::uuid(),
            'company_name' => 'Kebun Raya Cibodas',
            'company_address' => 'Jl. Kebun Raya Cibodas, Cianjur',
        ]);

        Company::create([
            'company_id' => Str::uuid(),
            'company_name' => 'Kebun Raya Purwodadi',
            'company_address' => 'Jl. Raya Surabaya-Malang Km. 65, Purwodadi, Pasuruan',
        ]);

        Company::create([
            'company_id' => Str::uuid(),
            'company_name' => 'Kebun Raya Bali',
            'company_address' => 'Jl. Kebun Raya, Candikuning, Baturiti, Tabanan, Bali',
        ]);
    }
}
