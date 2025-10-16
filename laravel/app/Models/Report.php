<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'report_id',          
        'company_id',
        'user_id',
        'item_id',
        'category_id',
        'report_type',
        'item_name',          
        'report_description',
        'report_datetime',
        'report_location',
        'report_status',
        'photo_url',          
        'reporter_name',      
        'reporter_phone',     
        'reporter_email', 
    ];

    protected $casts = [
        'report_datetime' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->report_id)) {
                $model->report_id = (string) Str::uuid();
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }


    public function claims()
    {
        return $this->hasMany(Claim::class, 'report_id', 'report_id');
    }
    
    public function matchesAsLost()
    {
        return $this->hasMany(MatchedItem::class, 'lost_report_id', 'report_id');
    }

    public function matchesAsFound()
    {
        return $this->hasMany(MatchedItem::class, 'found_report_id', 'report_id');
    }

    public function matches()
    {
        return $this->report_type === 'LOST' 
            ? $this->matchesAsLost() 
            : $this->matchesAsFound();
    }

    public function confirmedMatch()
    {
        return $this->matches()->where('match_status', 'CONFIRMED')->first();
    }

    public function hasMatches()
    {
        return $this->matches()->exists();
    }

    public function hasConfirmedMatch()
    {
        return $this->matches()->where('match_status', 'CONFIRMED')->exists();
    }

    /**
     * Use report_id for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'report_id';
    }
}
