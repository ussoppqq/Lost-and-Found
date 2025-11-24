<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Claim extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'claim_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'claim_id',
        'company_id',
        'match_id',
        'user_id',
        'item_id',
        'report_id',
        'claim_status',
        'brand',
        'color',
        'claim_notes',
        'claim_photos',
        'pickup_schedule',
        'processed_by',
        'processed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'claim_photos' => 'array',
        'pickup_schedule' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function match()
    {
        return $this->belongsTo(MatchedItem::class, 'match_id', 'match_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'report_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by', 'user_id');
    }

    // Status Checkers
    public function isPending()
    {
        return $this->claim_status === 'PENDING';
    }

    public function isReleased()
    {
        return $this->claim_status === 'RELEASED';
    }

    public function isRejected()
    {
        return $this->claim_status === 'REJECTED';
    }

    // Badge Class untuk UI
    public function getStatusBadgeClass()
    {
        return match($this->claim_status) {
            'PENDING' => 'bg-yellow-100 text-yellow-800',
            'RELEASED' => 'bg-green-100 text-green-800',
            'REJECTED' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Scope untuk filtering
    public function scopePending($query)
    {
        return $query->where('claim_status', 'PENDING');
    }

    public function scopeReleased($query)
    {
        return $query->where('claim_status', 'RELEASED');
    }

    public function scopeRejected($query)
    {
        return $query->where('claim_status', 'REJECTED');
    }
}