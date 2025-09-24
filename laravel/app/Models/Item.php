<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'company_id', 'post_id', 'category_id', 'item_name',
        'brand', 'color', 'item_description', 'storage',
        'item_status', 'retention_until', 'sensitivity_level'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function photos()
    {
        return $this->hasMany(ItemPhoto::class, 'item_id', 'item_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'item_id', 'item_id');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'item_id', 'item_id');
    }
}
