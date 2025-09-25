<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'user_id';   // ✅ primary key
    public $incrementing = false;        // ✅ UUID bukan auto-increment
    protected $keyType = 'string';       // ✅ UUID tipe string

    protected $fillable = [
        'company_id', 'role_id', 'full_name', 'email', 
        'phone_number', 'password', 'is_verified'
    ];

    protected $hidden = ['password'];

    public function company()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

