<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Report;
use App\Models\User;
use App\Models\Company;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $company1 = Company::first();
        $user1 = User::where('email', 'user@example.com')->first();
        $user2 = User::where('email', 'moderator.bogor@kebunraya.com')->first();

        // Report 1 (FOUND, linked ke item nanti)
        Report::create([
            'report_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'user_id' => $user1->user_id,
            'item_id' => null, // nanti update di ItemSeeder
            'report_type' => 'FOUND',
            'report_description' => 'Ditemukan HP Samsung di dekat kolam teratai',
            'report_datetime' => now()->subDays(2),
            'report_location' => 'Area Kolam Teratai',
            'report_status' => 'STORED',
        ]);

        // Report 2 (LOST, belum ada item fisik)
        Report::create([
            'report_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'user_id' => $user2->user_id,
            'item_id' => null,
            'report_type' => 'LOST',
            'report_description' => 'Kehilangan dompet coklat merk Fossil',
            'report_datetime' => now()->subDays(1),
            'report_location' => 'Area Parkir Utama',
            'report_status' => 'OPEN',
        ]);

        // Report 3 (FOUND by staff, no user)
        Report::create([
            'report_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'user_id' => null,
            'item_id' => null,
            'report_type' => 'FOUND',
            'report_description' => 'Tas ransel biru ditemukan petugas',
            'report_datetime' => now()->subHours(5),
            'report_location' => 'Taman Anggrek',
            'report_status' => 'STORED',
        ]);

        // Report 4 (LOST, no item yet)
        Report::create([
            'report_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'user_id' => $user1->user_id,
            'item_id' => null,
            'report_type' => 'LOST',
            'report_description' => 'Kehilangan kunci motor Honda Beat',
            'report_datetime' => now()->subHours(3),
            'report_location' => 'Dekat Toilet Umum',
            'report_status' => 'OPEN',
        ]);

        // Report 5 (FOUND, akan claimed)
        Report::create([
            'report_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'user_id' => $user2->user_id,
            'item_id' => null,
            'report_type' => 'FOUND',
            'report_description' => 'Dompet ditemukan di bangku taman',
            'report_datetime' => now()->subDays(5),
            'report_location' => 'Bangku Taman Utama',
            'report_status' => 'MATCHED',
        ]);
    }
}
