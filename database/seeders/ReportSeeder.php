<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\Company;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        if ($companies->isEmpty()) {
            $this->command->error('No companies found. Please run CompanySeeder first.');
            return;
        }

        // Data barang berbeda untuk setiap company
        $reportData = [
            'Kebun Raya Bogor' => [
                'lost' => [
                    ['item' => 'iPhone 13 Pro Max Black', 'category' => 'Phone', 'desc' => 'iPhone 13 Pro Max warna hitam dengan case kulit coklat', 'location' => 'Taman Teijsmann', 'date' => '2025-12-15 14:30:00', 'reporter' => 'Budi Santoso', 'phone' => '628123456789'],
                    ['item' => 'Dompet Kulit Coklat', 'category' => 'Wallet', 'desc' => 'Dompet kulit coklat merk Fossil berisi KTP dan kartu ATM', 'location' => 'Kolam Teratai', 'date' => '2025-12-16 10:15:00', 'reporter' => 'Siti Nurhaliza', 'phone' => '628234567890'],
                    ['item' => 'Kacamata Hitam Ray-Ban', 'category' => 'Glasses', 'desc' => 'Kacamata hitam Ray-Ban Aviator dengan frame emas', 'location' => 'Pintu Masuk Utama', 'date' => '2025-12-17 09:00:00', 'reporter' => 'Ahmad Wijaya', 'phone' => '628345678901'],
                ],
                'found' => [
                    ['item' => 'Tas Ransel Hitam', 'category' => 'Bag', 'desc' => 'Tas ransel hitam merk Eiger berisi buku dan payung', 'location' => 'Taman Meksiko', 'date' => '2025-12-15 15:00:00', 'reporter' => 'Petugas A', 'phone' => '628456789012'],
                    ['item' => 'Jam Tangan Casio G-Shock', 'category' => 'Watch', 'desc' => 'Jam tangan Casio G-Shock warna biru navy', 'location' => 'Area Parkir', 'date' => '2025-12-16 16:30:00', 'reporter' => 'Petugas B', 'phone' => '628567890123'],
                    ['item' => 'Power Bank Xiaomi 10000mAh', 'category' => 'Others', 'desc' => 'Power bank Xiaomi warna putih dengan kabel charger', 'location' => 'Kantin', 'date' => '2025-12-17 12:00:00', 'reporter' => 'Petugas C', 'phone' => '628678901234'],
                ],
            ],
            'Kebun Raya Cibodas' => [
                'lost' => [
                    ['item' => 'Kamera Canon EOS M50', 'category' => 'Camera', 'desc' => 'Kamera Canon EOS M50 dengan tas dan lensa kit', 'location' => 'Air Terjun Cibodas', 'date' => '2025-12-14 11:20:00', 'reporter' => 'Dewi Lestari', 'phone' => '628789012345'],
                    ['item' => 'Jaket Gunung North Face', 'category' => 'Others', 'desc' => 'Jaket gunung The North Face warna merah ukuran L', 'location' => 'Taman Rhododendron', 'date' => '2025-12-15 13:45:00', 'reporter' => 'Rudi Hermawan', 'phone' => '628890123456'],
                    ['item' => 'Kunci Mobil Toyota', 'category' => 'Keys', 'desc' => 'Kunci mobil Toyota Avanza dengan gantungan boneka', 'location' => 'Parkiran Utama', 'date' => '2025-12-16 17:00:00', 'reporter' => 'Linda Susanti', 'phone' => '628901234567'],
                ],
                'found' => [
                    ['item' => 'Botol Minum Stainless', 'category' => 'Others', 'desc' => 'Botol minum stainless warna hijau merk Thermos', 'location' => 'Jalur Hiking', 'date' => '2025-12-14 12:00:00', 'reporter' => 'Ranger A', 'phone' => '629012345678'],
                    ['item' => 'Payung Lipat Hitam', 'category' => 'Others', 'desc' => 'Payung lipat warna hitam dengan motif kotak-kotak', 'location' => 'Shelter Istirahat', 'date' => '2025-12-15 14:30:00', 'reporter' => 'Ranger B', 'phone' => '629123456789'],
                    ['item' => 'Earphone Samsung', 'category' => 'Headphones', 'desc' => 'Earphone Samsung Galaxy Buds warna putih dengan case', 'location' => 'Pos Informasi', 'date' => '2025-12-16 18:00:00', 'reporter' => 'Ranger C', 'phone' => '629234567890'],
                ],
            ],
            'Kebun Raya Purwodadi' => [
                'lost' => [
                    ['item' => 'Laptop Asus ROG', 'category' => 'Laptop', 'desc' => 'Laptop Asus ROG Strix G15 dengan tas laptop hitam', 'location' => 'Taman Kaktus', 'date' => '2025-12-13 15:30:00', 'reporter' => 'Eko Prasetyo', 'phone' => '629345678901'],
                    ['item' => 'Kalung Emas', 'category' => 'Jewelry', 'desc' => 'Kalung emas 18 karat dengan liontin hati', 'location' => 'Taman Palem', 'date' => '2025-12-14 10:00:00', 'reporter' => 'Maya Anggraini', 'phone' => '629456789012'],
                    ['item' => 'Buku Novel Harry Potter', 'category' => 'Books', 'desc' => 'Buku Harry Potter and the Philosopher\'s Stone edisi bahasa Inggris', 'location' => 'Gazebo Taman', 'date' => '2025-12-15 11:30:00', 'reporter' => 'Arif Rahman', 'phone' => '629567890123'],
                ],
                'found' => [
                    ['item' => 'Topi Baseball Yankees', 'category' => 'Others', 'desc' => 'Topi baseball NY Yankees warna biru dongker', 'location' => 'Area Bermain Anak', 'date' => '2025-12-13 16:00:00', 'reporter' => 'Petugas Keamanan A', 'phone' => '629678901234'],
                    ['item' => 'Tablet iPad Mini', 'category' => 'Phone', 'desc' => 'iPad Mini generasi 5 warna silver dengan smart cover', 'location' => 'Food Court', 'date' => '2025-12-14 11:00:00', 'reporter' => 'Petugas Keamanan B', 'phone' => '629789012345'],
                    ['item' => 'Sarung Tangan Kulit', 'category' => 'Others', 'desc' => 'Sepasang sarung tangan kulit warna hitam ukuran M', 'location' => 'Toilet Umum', 'date' => '2025-12-15 12:30:00', 'reporter' => 'Cleaning Service', 'phone' => '629890123456'],
                ],
            ],
            'Kebun Raya Bali' => [
                'lost' => [
                    ['item' => 'Smartwatch Apple Watch', 'category' => 'Watch', 'desc' => 'Apple Watch Series 7 warna midnight dengan sport band', 'location' => 'Taman Begonia', 'date' => '2025-12-12 14:00:00', 'reporter' => 'Made Wirawan', 'phone' => '629901234567'],
                    ['item' => 'Tas Koper Kecil', 'category' => 'Luggage', 'desc' => 'Koper ukuran cabin warna ungu merk American Tourister', 'location' => 'Area Parkir Bus', 'date' => '2025-12-13 09:30:00', 'reporter' => 'Ni Ketut Ayu', 'phone' => '620123456789'],
                    ['item' => 'Kartu Identitas (KTP)', 'category' => 'Documents', 'desc' => 'KTP atas nama I Wayan Suardika', 'location' => 'Pos Tiket Masuk', 'date' => '2025-12-14 08:00:00', 'reporter' => 'I Wayan Suardika', 'phone' => '620234567890'],
                ],
                'found' => [
                    ['item' => 'Sandal Jepit Havaianas', 'category' => 'Others', 'desc' => 'Sandal jepit Havaianas warna kuning ukuran 39', 'location' => 'Taman Anggrek', 'date' => '2025-12-12 15:00:00', 'reporter' => 'Petugas Kebersihan A', 'phone' => '620345678901'],
                    ['item' => 'Headphone Sony WH-1000XM4', 'category' => 'Headphones', 'desc' => 'Headphone wireless Sony WH-1000XM4 warna hitam dengan case', 'location' => 'Cafe Danau', 'date' => '2025-12-13 10:30:00', 'reporter' => 'Staff Cafe', 'phone' => '620456789012'],
                    ['item' => 'Gelang Perak', 'category' => 'Jewelry', 'desc' => 'Gelang perak dengan ukiran bunga mawar', 'location' => 'Musholla', 'date' => '2025-12-14 09:00:00', 'reporter' => 'Petugas Kebersihan B', 'phone' => '620567890123'],
                ],
            ],
        ];

        $reportNumber = 1;

        foreach ($companies as $company) {
            if (!isset($reportData[$company->company_name])) {
                $this->command->warn("No report data found for {$company->company_name}");
                continue;
            }

            $companyData = $reportData[$company->company_name];

            // Create Lost Reports
            foreach ($companyData['lost'] as $lostItem) {
                $category = Category::where('company_id', $company->company_id)
                    ->where('category_name', $lostItem['category'])
                    ->first();

                if (!$category) {
                    $this->command->warn("Category {$lostItem['category']} not found for {$company->company_name}");
                    continue;
                }

                Report::create([
                    'report_id' => Str::uuid(),
                    'report_number' => $reportNumber++,
                    'company_id' => $company->company_id,
                    'user_id' => null, // Guest report
                    'category_id' => $category->category_id,
                    'report_type' => 'LOST',
                    'item_name' => $lostItem['item'],
                    'report_description' => $lostItem['desc'],
                    'report_datetime' => Carbon::parse($lostItem['date']),
                    'report_location' => $lostItem['location'],
                    'report_status' => 'OPEN',
                    'reporter_name' => $lostItem['reporter'],
                    'reporter_phone' => $lostItem['phone'],
                ]);
            }

            // Create Found Reports
            foreach ($companyData['found'] as $foundItem) {
                $category = Category::where('company_id', $company->company_id)
                    ->where('category_name', $foundItem['category'])
                    ->first();

                if (!$category) {
                    $this->command->warn("Category {$foundItem['category']} not found for {$company->company_name}");
                    continue;
                }

                Report::create([
                    'report_id' => Str::uuid(),
                    'report_number' => $reportNumber++,
                    'company_id' => $company->company_id,
                    'user_id' => null, // Guest report
                    'category_id' => $category->category_id,
                    'report_type' => 'FOUND',
                    'item_name' => $foundItem['item'],
                    'report_description' => $foundItem['desc'],
                    'report_datetime' => Carbon::parse($foundItem['date']),
                    'report_location' => $foundItem['location'],
                    'report_status' => 'OPEN',
                    'reporter_name' => $foundItem['reporter'],
                    'reporter_phone' => $foundItem['phone'],
                ]);
            }

            $this->command->info("✓ Created " . (count($companyData['lost']) + count($companyData['found'])) . " reports for {$company->company_name}");
        }

        $this->command->info("✅ Successfully created total of " . ($reportNumber - 1) . " reports across all companies");
    }
}
