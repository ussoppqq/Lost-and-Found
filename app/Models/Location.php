<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $primaryKey = 'location_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // migration tidak ada timestamps

    protected $fillable = ['name', 'area'];
}
