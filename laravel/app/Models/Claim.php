<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Claim extends Model
{
    use HasUuids;

    protected $primaryKey = 'claim_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'claim_id',
        'company_id',
        'user_id',
        'item_id',
        'report_id',
        'brand',
        'color',
        'claim_notes',
        'claim_photos', 
        'claim_status',
        'pickup_schedule',
    ];

    protected $casts = [
        'claim_photos' => 'array', 
        'pickup_schedule' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}