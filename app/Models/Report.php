<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'report_id',
        'report_number',      
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
            
            // Auto-generate report_number
            if (empty($model->report_number)) {
                $lastReport = static::orderBy('report_number', 'desc')->first();
                $model->report_number = $lastReport ? $lastReport->report_number + 1 : 1;
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

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

    /**
     * Get all photos associated with this report
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ReportPhoto::class, 'report_id', 'report_id')
                    ->orderBy('photo_order');
    }

    /**
     * Get the primary photo of this report
     */
    public function primaryPhoto(): HasOne
    {
        return $this->hasOne(ReportPhoto::class, 'report_id', 'report_id')
                    ->where('is_primary', true);
    }

    // ==================== HELPER METHODS ====================

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
     * Get formatted report ID (e.g., #001, #123)
     */
    public function getFormattedReportNumberAttribute()
    {
        return '#' . str_pad($this->report_number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Use report_id for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'report_id';
    }
}