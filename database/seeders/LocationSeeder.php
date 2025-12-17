<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
                'area_name'   => $location['name'],
                'area'        => $location['area'],
            ]);
        }
    }
}
