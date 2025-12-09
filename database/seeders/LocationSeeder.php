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
        Location::create([
            'location_id' => Str::uuid(),
            'area_name'   => 'Main Entrance',
            'latitude'    => -6.597147,
            'longitude'   => 106.799404,
        ]);

        Location::create([
            'location_id' => Str::uuid(),
            'area_name'   => 'Lotus Pond',
            'latitude'    => -6.598234,
            'longitude'   => 106.800123,
        ]);

        Location::create([
            'location_id' => Str::uuid(),
            'area_name'   => 'Orchid Garden',
            'latitude'    => -6.599456,
            'longitude'   => 106.801234,
        ]);
    }
}
