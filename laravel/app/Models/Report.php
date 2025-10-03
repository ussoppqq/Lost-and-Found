<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'report_type',
        // 'item_name',          
        'report_description',
        'report_datetime',
        'report_location',
        'report_status',
        'photo_url',          
        'reporter_name',      
        'reporter_phone',     
        'reporter_email', 
    ];

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

    public function claims()
    {
        return $this->hasMany(Claim::class, 'report_id', 'report_id');
    }
}
