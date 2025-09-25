<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'company_id', 
        'role_id', 
        'full_name', 
        'email', 
        'phone_number', 
        'password', 
        'is_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🔑 Auto generate UUID untuk user_id
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // 🔗 Relasi ke Company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    // 🔗 Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
