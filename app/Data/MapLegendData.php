<?php

namespace App\Data;

class MapLegendData
{
    public static function getFacilities(): array
    {
        return [
            ['number' => 1, 'name' => 'Gerbang Utama / Main Gate'],
            ['number' => 2, 'name' => 'Pusat Informasi / Information Center'],
            ['number' => 3, 'name' => 'Museum Zoology / Zoological Museum'],
            ['number' => 4, 'name' => 'Gedung Konservasi / Conservation Building'],
            ['number' => 5, 'name' => 'Hotel'],
            ['number' => 6, 'name' => 'Laboratorium Treub / Treub Laboratory'],
            ['number' => 7, 'name' => 'Toko Merchandise / Merchandise Store'],
            ['number' => 8, 'name' => 'Pembibitan / Nursery'],
            ['number' => 9, 'name' => 'Pintu II / Gate II'],
            ['number' => 10, 'name' => 'Kantor Pengelola / Main Office'],
            ['number' => 11, 'name' => 'Masjid / Mosque'],
            ['number' => 12, 'name' => 'Gedung Herbarium & Museum Biji / Herbarium & Seeds Museum'],
            ['number' => 13, 'name' => 'Pintu III / Gate III'],
            ['number' => 14, 'name' => 'Restoran / Restaurant'],
            ['number' => 15, 'name' => 'Pembibitan Anggrek / Orchid Nursery'],
            ['number' => 16, 'name' => 'Laboratorium Kultur Jaringan / Tissue Culture Laboratory'],
            ['number' => 17, 'name' => 'Pintu IV / Gate IV'],
            ['number' => 18, 'name' => 'Musholla / Mosque'],
            ['number' => 19, 'name' => 'Pembibitan Reintroduksi & Tumbuhan Langka / Reintroduction & Rare Plants Nursery'],
            ['number' => 20, 'name' => 'Auditorium Rafflesia'],
        ];
    }

    public static function getAvenues(): array
    {
        return [
            ['number' => 21, 'name' => 'Melchior Avenue'],
            ['number' => 22, 'name' => 'Little Melchior Avenue'],
            ['number' => 23, 'name' => 'Astrid Avenue'],
            ['number' => 24, 'name' => 'Cappelen Avenue'],
            ['number' => 25, 'name' => 'Reindwart Avenue'],
            ['number' => 26, 'name' => 'Otto Avenue'],
        ];
    }

    public static function getInterestingSites(): array
    {
        return [
            ['number' => 27, 'name' => 'Monumen Lady Raffles / Lady Raffles Memorial'],
            ['number' => 28, 'name' => 'Monumen J.J. Smith / J.J. Smith Memorial'],
            ['number' => 29, 'name' => 'Kolam Gunting / Scissor Pond'],
            ['number' => 30, 'name' => 'Taman Teisjmann / Teisjmann Park'],
            ['number' => 31, 'name' => 'Makam Belanda / Dutch Cemetery'],
            ['number' => 32, 'name' => 'Istana Bogor / Bogor Palace'],
            ['number' => 33, 'name' => 'Jalan Kenari I / Canary Avenue I'],
            ['number' => 34, 'name' => 'Jalan Kenari II / Canary Avenue II'],
            ['number' => 35, 'name' => 'Jembatan Gantung / Hanging Bridge'],
            ['number' => 36, 'name' => 'Jembatan Surya Lembayung / Surya Lembayung Bridge'],
            ['number' => 37, 'name' => 'Jalan Astrid / Astrid Avenue'],
            ['number' => 38, 'name' => 'Griya Anggrek / Orchid House'],
            ['number' => 39, 'name' => 'Taman Lebak Sudjana Kassan / Sudjana Kassan Park'],
            ['number' => 40, 'name' => 'Area Pinus / Pine Area'],
        ];
    }

    public static function getPlantCollections(): array
    {
        return [
            ['number' => 41, 'name' => 'Koleksi Tumbuhan Obat / Medicinal Plant Collection'],
            ['number' => 42, 'name' => 'Kayu Raja / King Tree / Koompassia excelsa'],
            ['number' => 43, 'name' => 'Koleksi Tumbuhan Aracea / Araceae Plant Collection'],
            ['number' => 44, 'name' => 'Bunga Bangkai / Amorphophallus titanium'],
            ['number' => 45, 'name' => 'Rotan / Rattan'],
            ['number' => 46, 'name' => 'Koleksi Pandan / Pandanus Collection'],
            ['number' => 47, 'name' => 'Koleksi Kaktus (Taman Meksiko) / Cactus Collection (Mexican Garden)'],
            ['number' => 48, 'name' => 'Koleksi Palem / Palm Collection'],
            ['number' => 49, 'name' => 'Koleksi Tanaman Air / Water Plant Collection'],
            ['number' => 50, 'name' => 'Koleksi Anggrek / Orchidarium'],
            ['number' => 51, 'name' => 'Koleksi Tanaman Pemanjat / Climbing Plant Collection'],
            ['number' => 52, 'name' => 'Koleksi Paku-pakuan / Fern Collection'],
            ['number' => 53, 'name' => 'Hutan / Wild Corner'],
            ['number' => 54, 'name' => 'Koleksi Kayu Manis / Cinnamomum Collection'],
            ['number' => 55, 'name' => 'Teratai Raksasa / Victoria amazonica'],
            ['number' => 56, 'name' => 'Koleksi Tanaman Kayu / Wood Plant Collection'],
            ['number' => 57, 'name' => 'Koleksi Bambu / Bamboo Collection'],
        ];
    }

    public static function getMapPins(): array
    {
        return [
            // FACILITIES (Blue - 37, 99, 235)
            ['id' => 1, 'name' => 'Gerbang Utama', 'subtitle' => 'Main Gate', 'top' => '89.09%', 'left' => '39.62%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 2, 'name' => 'Pusat Informasi', 'subtitle' => 'Information Center', 'top' => '86.19%', 'left' => '45.56%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 3, 'name' => 'Museum Zoology', 'subtitle' => 'Zoological Museum', 'top' => '92.24%', 'left' => '33.20%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 4, 'name' => 'Gedung Konservasi', 'subtitle' => 'Conservation Building', 'top' => '90.55%', 'left' => '36.35%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 5, 'name' => 'Hotel', 'subtitle' => 'Hotel', 'top' => '87.16%', 'left' => '32.11%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 6, 'name' => 'Lab Treub', 'subtitle' => 'Treub Laboratory', 'top' => '81.84%', 'left' => '35.74%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 7, 'name' => 'Toko Merchandise', 'subtitle' => 'Merchandise Store', 'top' => '81.60%', 'left' => '38.53%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 8, 'name' => 'Pembibitan', 'subtitle' => 'Nursery', 'top' => '77.97%', 'left' => '37.20%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 9, 'name' => 'Pintu II', 'subtitle' => 'Gate II', 'top' => '54.99%', 'left' => '28.47%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 10, 'name' => 'Kantor Pengelola', 'subtitle' => 'Main Office', 'top' => '47.01%', 'left' => '31.74%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 11, 'name' => 'Masjid', 'subtitle' => 'Mosque', 'top' => '48.46%', 'left' => '62.04%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 12, 'name' => 'Herbarium & Museum Biji', 'subtitle' => 'Herbarium & Seeds Museum', 'top' => '32.25%', 'left' => '62.76%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 13, 'name' => 'Pintu III', 'subtitle' => 'Gate III', 'top' => '40.72%', 'left' => '70.88%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 14, 'name' => 'Restoran', 'subtitle' => 'Restaurant', 'top' => '66.36%', 'left' => '64.70%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 15, 'name' => 'Pembibitan Anggrek', 'subtitle' => 'Orchid Nursery', 'top' => '35.88%', 'left' => '66.03%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 16, 'name' => 'Lab Kultur Jaringan', 'subtitle' => 'Tissue Culture Lab', 'top' => '33.70%', 'left' => '67.85%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 17, 'name' => 'Pintu IV', 'subtitle' => 'Gate IV', 'top' => '76.03%', 'left' => '70.40%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 18, 'name' => 'Musholla', 'subtitle' => 'Prayer Room', 'top' => '86.68%', 'left' => '35.50%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 19, 'name' => 'Pembibitan Reintroduksi', 'subtitle' => 'Reintroduction Nursery', 'top' => '20.88%', 'left' => '60.46%', 'color' => '37, 99, 235', 'category' => 'facility'],
            ['id' => 20, 'name' => 'Auditorium Rafflesia', 'subtitle' => 'Auditorium', 'top' => '88.85%', 'left' => '36.47%', 'color' => '37, 99, 235', 'category' => 'facility'],

            // AVENUES (Orange - 249, 115, 22)
            ['id' => 21, 'name' => 'Melchior Avenue', 'subtitle' => 'Main Avenue', 'top' => '78.69%', 'left' => '32.71%', 'color' => '249, 115, 22', 'category' => 'avenue'],
            ['id' => 22, 'name' => 'Little Melchior Ave', 'subtitle' => 'Avenue', 'top' => '80.87%', 'left' => '29.32%', 'color' => '249, 115, 22', 'category' => 'avenue'],
            ['id' => 23, 'name' => 'Astrid Avenue', 'subtitle' => 'Avenue', 'top' => '68.05%', 'left' => '61.07%', 'color' => '249, 115, 22', 'category' => 'avenue'],
            ['id' => 24, 'name' => 'Cappelen Avenue', 'subtitle' => 'Avenue', 'top' => '51.36%', 'left' => '68.58%', 'color' => '249, 115, 22', 'category' => 'avenue'],
            ['id' => 25, 'name' => 'Reindwart Avenue', 'subtitle' => 'Avenue', 'top' => '47.25%', 'left' => '65.55%', 'color' => '249, 115, 22', 'category' => 'avenue'],
            ['id' => 26, 'name' => 'Otto Avenue', 'subtitle' => 'Avenue', 'top' => '82.81%', 'left' => '37.20%', 'color' => '249, 115, 22', 'category' => 'avenue'],

            // INTERESTING SITES (Purple - 147, 51, 234)
            ['id' => 27, 'name' => 'Monumen Lady Raffles', 'subtitle' => 'Historical Monument', 'top' => '77.97%', 'left' => '43.50%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 28, 'name' => 'Monumen J.J. Smith', 'subtitle' => 'Historical Monument', 'top' => '77.48%', 'left' => '47.25%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 29, 'name' => 'Kolam Gunting', 'subtitle' => 'Scissor Pond', 'top' => '69.99%', 'left' => '40.59%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 30, 'name' => 'Taman Teisjmann', 'subtitle' => 'Teisjmann Park', 'top' => '66.84%', 'left' => '30.78%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 31, 'name' => 'Makam Belanda', 'subtitle' => 'Dutch Cemetery', 'top' => '57.89%', 'left' => '33.32%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 32, 'name' => 'Istana Bogor', 'subtitle' => 'Bogor Palace', 'top' => '32.74%', 'left' => '43.98%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 33, 'name' => 'Jalan Kenari I', 'subtitle' => 'Canary Avenue I', 'top' => '71.44%', 'left' => '38.65%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 34, 'name' => 'Jalan Kenari II', 'subtitle' => 'Canary Avenue II', 'top' => '73.37%', 'left' => '57.92%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 35, 'name' => 'Jembatan Gantung', 'subtitle' => 'Hanging Bridge', 'top' => '42.17%', 'left' => '57.07%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 36, 'name' => 'Jembatan Surya Lembayung', 'subtitle' => 'Bridge', 'top' => '12.90%', 'left' => '59.01%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 37, 'name' => 'Jalan Astrid', 'subtitle' => 'Astrid Avenue', 'top' => '52.33%', 'left' => '66.52%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 38, 'name' => 'Griya Anggrek', 'subtitle' => 'Orchid House', 'top' => '42.65%', 'left' => '68.82%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 39, 'name' => 'Taman Sudjana Kassan', 'subtitle' => 'Park', 'top' => '10.48%', 'left' => '60.46%', 'color' => '147, 51, 234', 'category' => 'site'],
            ['id' => 40, 'name' => 'Area Pinus', 'subtitle' => 'Pine Area', 'top' => '55.23%', 'left' => '70.64%', 'color' => '147, 51, 234', 'category' => 'site'],

            // PLANT COLLECTIONS (Green - 22, 163, 74)
            ['id' => 41, 'name' => 'Tumbuhan Obat', 'subtitle' => 'Medicinal Plants', 'top' => '18.46%', 'left' => '62.76%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 42, 'name' => 'Kayu Raja', 'subtitle' => 'King Tree', 'top' => '26.69%', 'left' => '62.40%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 43, 'name' => 'Tumbuhan Aracea', 'subtitle' => 'Araceae Collection', 'top' => '36.85%', 'left' => '51.13%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 44, 'name' => 'Bunga Bangkai', 'subtitle' => 'Titan Arum', 'top' => '46.52%', 'left' => '56.22%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 45, 'name' => 'Rotan', 'subtitle' => 'Rattan Collection', 'top' => '55.71%', 'left' => '60.95%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 46, 'name' => 'Koleksi Pandan', 'subtitle' => 'Pandanus Collection', 'top' => '60.79%', 'left' => '28.59%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 47, 'name' => 'Taman Meksiko', 'subtitle' => 'Cactus Collection', 'top' => '53.78%', 'left' => '49.56%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 48, 'name' => 'Koleksi Palem', 'subtitle' => 'Palm Collection', 'top' => '71.44%', 'left' => '51.74%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 49, 'name' => 'Tanaman Air', 'subtitle' => 'Water Plants', 'top' => '71.92%', 'left' => '30.41%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 50, 'name' => 'Koleksi Anggrek', 'subtitle' => 'Orchidarium', 'top' => '78.69%', 'left' => '33.80%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 51, 'name' => 'Tanaman Pemanjat', 'subtitle' => 'Climbing Plants', 'top' => '80.63%', 'left' => '48.47%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 52, 'name' => 'Paku-pakuan', 'subtitle' => 'Fern Collection', 'top' => '81.84%', 'left' => '42.16%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 53, 'name' => 'Hutan', 'subtitle' => 'Wild Corner', 'top' => '85.22%', 'left' => '50.89%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 54, 'name' => 'Kayu Manis', 'subtitle' => 'Cinnamomum', 'top' => '55.23%', 'left' => '31.62%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 55, 'name' => 'Teratai Raksasa', 'subtitle' => 'Victoria amazonica', 'top' => '73.13%', 'left' => '69.18%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 56, 'name' => 'Tanaman Kayu', 'subtitle' => 'Wood Plants', 'top' => '85.47%', 'left' => '66.88%', 'color' => '22, 163, 74', 'category' => 'collection'],
            ['id' => 57, 'name' => 'Koleksi Bambu', 'subtitle' => 'Bamboo Collection', 'top' => '86.68%', 'left' => '69.31%', 'color' => '22, 163, 74', 'category' => 'collection'],
        ];
    }
}