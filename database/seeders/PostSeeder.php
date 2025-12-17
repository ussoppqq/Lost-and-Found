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
        $companies = Company::all();
        $bogor = $companies->where('company_name', 'Kebun Raya Bogor')->first();
        $cibodas = $companies->where('company_name', 'Kebun Raya Cibodas')->first();
        $purwodadi = $companies->where('company_name', 'Kebun Raya Purwodadi')->first();
        $bali = $companies->where('company_name', 'Kebun Raya Bali')->first();

        // Post untuk Kebun Raya Bogor
        Post::create([
            'post_id' => Str::uuid(),
            'company_id' => $bogor->company_id,
            'post_name' => 'Post Bogor - Gerbang Utama',
            'post_address' => 'Gerbang Utama Kebun Raya Bogor',
            'capacity' => 100,
        ]);

        // Post untuk Kebun Raya Cibodas
        Post::create([
            'post_id' => Str::uuid(),
            'company_id' => $cibodas->company_id,
            'post_name' => 'Post Cibodas - Pusat Informasi',
            'post_address' => 'Pusat Informasi Kebun Raya Cibodas',
            'capacity' => 80,
        ]);

        // Post untuk Kebun Raya Purwodadi
        Post::create([
            'post_id' => Str::uuid(),
            'company_id' => $purwodadi->company_id,
            'post_name' => 'Post Purwodadi - Area Koleksi',
            'post_address' => 'Area Koleksi Kebun Raya Purwodadi',
            'capacity' => 75,
        ]);

        // Post untuk Kebun Raya Bali
        Post::create([
            'post_id' => Str::uuid(),
            'company_id' => $bali->company_id,
            'post_name' => 'Post Bali - Gerbang Masuk',
            'post_address' => 'Gerbang Masuk Kebun Raya Bali',
            'capacity' => 90,
        ]);
    }
}
