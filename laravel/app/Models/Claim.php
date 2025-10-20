<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Claim extends Model
{
    protected $primaryKey = 'claim_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'claim_id',
        'company_id',
        'user_id',
        'match_id',      
        'item_id',       
        'report_id',     
        'claim_status',
        'pickup_schedule',
        'brand',
        'color',
        'claim_notes',
        'claim_photos',  
    ];

    protected $casts = [
        'pickup_schedule' => 'datetime',
        'claim_photos' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->claim_id)) {
                $model->claim_id = (string) Str::uuid();
            }
        });
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function match()
    {
        return $this->belongsTo(MatchedItem::class, 'match_id', 'match_id');
    }

    // Item dari found report (ALWAYS exists)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    // Lost report reference
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'report_id');
    }

    // Helper methods - Simplified karena data dari match
    public function getLostReport()
    {
        return $this->match->lostReport;
    }

    public function getFoundReport()
    {
        return $this->match->foundReport;
    }

    public function getFoundItem()
    {
        return $this->item; 
    }

    public function getStatusBadgeClass()
    {
        return match($this->claim_status) {
            'PENDING' => 'bg-yellow-100 text-yellow-800',
            'APPROVED' => 'bg-green-100 text-green-800',
            'REJECTED' => 'bg-red-100 text-red-800',
            'RELEASED' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function isPending()
    {
        return $this->claim_status === 'PENDING';
    }

    public function isApproved()
    {
        return $this->claim_status === 'APPROVED';
    }

    public function isReleased()
    {
        return $this->claim_status === 'RELEASED';
    }
}