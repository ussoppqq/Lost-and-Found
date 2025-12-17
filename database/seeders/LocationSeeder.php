<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Company;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * NOTE: Lokasi-lokasi ini adalah untuk Kebun Raya Bogor.
     * Untuk Kebun Raya lainnya (Cibodas, Purwodadi, Bali) belum diinput.
     */
    public function run(): void
    {
        // Get Kebun Raya Bogor company
        $bogorCompany = Company::where('company_name', 'Kebun Raya Bogor')->first();

        if (!$bogorCompany) {
            $this->command->error('Kebun Raya Bogor company not found. Please run CompanySeeder first.');
            return;
        }
        // Lokasi khusus untuk Kebun Raya Bogor
        $locations = [
            // Zona Fungsional Utama
            ['name' => 'Pintu Masuk Utama', 'area' => 'Zona Fungsional Utama'],
            ['name' => 'Pusat Informasi / Visitor Center', 'area' => 'Zona Fungsional Utama'],
            ['name' => 'Area Parkir', 'area' => 'Zona Fungsional Utama'],
            ['name' => 'Toko Souvenir / Gift Shop', 'area' => 'Zona Fungsional Utama'],
            ['name' => 'Kantor Pengelola', 'area' => 'Zona Fungsional Utama'],

            // Area Taman Tematik & Spot Menarik
            ['name' => 'Taman Teijsmann', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Meksiko', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Bambu', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Obat', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Orchidarium / Griya Anggrek', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Kolam Gunting', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Nepenthes', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Araceae', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Kopi', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Durian', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Palem', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Paku-pakuan', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Taman Akuatik', 'area' => 'Area Taman Tematik & Spot Menarik'],
            ['name' => 'Ecodome', 'area' => 'Area Taman Tematik & Spot Menarik'],

            // Situs Bersejarah & Area Ikonik
            ['name' => 'Istana Bogor', 'area' => 'Situs Bersejarah & Area Ikonik'],
            ['name' => 'Monumen Lady Raffles', 'area' => 'Situs Bersejarah & Area Ikonik'],
            ['name' => 'Monumen Reinwardt', 'area' => 'Situs Bersejarah & Area Ikonik'],
            ['name' => 'Makam Belanda (Olive Cemetery)', 'area' => 'Situs Bersejarah & Area Ikonik'],
            ['name' => 'Monumen J.J. Smith', 'area' => 'Situs Bersejarah & Area Ikonik'],
            ['name' => 'Museum Zoologi Bogor', 'area' => 'Situs Bersejarah & Area Ikonik'],
            ['name' => 'Jembatan Merah', 'area' => 'Situs Bersejarah & Area Ikonik'],
            ['name' => 'Jembatan Cinta', 'area' => 'Situs Bersejarah & Area Ikonik'],
        ];

        foreach ($locations as $location) {
            Location::create([
                'location_id' => Str::uuid(),
                'company_id'  => $bogorCompany->company_id,
                'name'        => $location['name'],
                'area'        => $location['area'],
            ]);
        }

        $this->command->info("✓ Created " . count($locations) . " locations for Kebun Raya Bogor");
    }
}
