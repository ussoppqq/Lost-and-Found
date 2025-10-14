<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MatchedItem extends Model
{
    protected $table = 'matches'; 
    protected $primaryKey = 'match_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'match_id',
        'lost_report_id',
        'found_report_id',
        'match_status',
        'confidence_score',
        'match_notes',
        'matched_by',
        'matched_at',
        'confirmed_at',
        'confirmed_by',
    ];

    protected $casts = [
        'matched_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'confidence_score' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->match_id)) {
                $model->match_id = (string) Str::uuid();
            }
            if (empty($model->matched_at)) {
                $model->matched_at = now();
            }
        });
    }

    // Relationships
    public function lostReport()
    {
        return $this->belongsTo(Report::class, 'lost_report_id', 'report_id');
    }

    public function foundReport()
    {
        return $this->belongsTo(Report::class, 'found_report_id', 'report_id');
    }

    public function matcher()
    {
        return $this->belongsTo(User::class, 'matched_by', 'user_id');
    }

    public function confirmer()
    {
        return $this->belongsTo(User::class, 'confirmed_by', 'user_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('match_status', 'PENDING');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('match_status', 'CONFIRMED');
    }

    public function scopeRejected($query)
    {
        return $query->where('match_status', 'REJECTED');
    }

    // Helper methods
    public function isPending()
    {
        return $this->match_status === 'PENDING';
    }

    public function isConfirmed()
    {
        return $this->match_status === 'CONFIRMED';
    }

    public function isRejected()
    {
        return $this->match_status === 'REJECTED';
    }

    public function getStatusBadgeClass()
    {
        return match($this->match_status) {
            'PENDING' => 'bg-yellow-100 text-yellow-800',
            'CONFIRMED' => 'bg-green-100 text-green-800',
            'REJECTED' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}