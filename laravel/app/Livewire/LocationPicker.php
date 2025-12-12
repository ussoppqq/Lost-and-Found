<?php

namespace App\Livewire;

use Livewire\Component;

class LocationPicker extends Component
{
    public $latitude;
    public $longitude;
    public $locationName = '';

    // Koordinat default: Kebun Raya Bogor
    public $defaultLat = -6.5972;
    public $defaultLng = 106.7989;

    public function mount($latitude = null, $longitude = null, $locationName = '')
    {
        $this->latitude = $latitude ?? $this->defaultLat;
        $this->longitude = $longitude ?? $this->defaultLng;
        $this->locationName = $locationName;
    }

    public function updateLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;

        // Emit event ke parent component
        $this->dispatch('locationUpdated', [
            'latitude' => $lat,
            'longitude' => $lng
        ]);
    }

    public function render()
    {
        return view('livewire.location-picker');
    }
}
