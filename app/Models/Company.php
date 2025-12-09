<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'company_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['company_name', 'company_address'];

    // Relasi
    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'company_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'company_id', 'company_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'company_id', 'company_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'company_id', 'company_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'company_id', 'company_id');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'company_id', 'company_id');
    }
}
