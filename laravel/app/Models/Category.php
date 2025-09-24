<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // karena migration tidak pakai timestamps

    protected $fillable = ['company_id', 'category_name', 'subcategory_name', 'retention_days', 'is_restricted'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'category_id', 'category_id');
    }
}
