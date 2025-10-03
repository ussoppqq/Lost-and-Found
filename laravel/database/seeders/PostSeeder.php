<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\Company;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $company1 = Company::first();

        Post::create([
            'post_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'post_name' => 'Main Gate Post',
            'post_address' => 'Gerbang Utama Kebun Raya',
            'capacity' => 100,
        ]);

        Post::create([
            'post_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'post_name' => 'Information Center',
            'post_address' => 'Pusat Informasi Kebun Raya',
            'capacity' => 50,
        ]);

        Post::create([
            'post_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'post_name' => 'Exit Gate Post',
            'post_address' => 'Gerbang Keluar Kebun Raya',
            'capacity' => 75,
        ]);
    }
}
