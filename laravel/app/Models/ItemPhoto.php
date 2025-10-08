<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPhoto extends Model
{
    use HasFactory;

    protected $primaryKey = 'photo_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['photo_id', 'company_id', 'item_id', 'photo_url', 'alt_text', 'display_order'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}
