<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';   // ✅ primary key
    public $incrementing = false;        // ✅ UUID bukan auto-increment
    protected $keyType = 'string';       // ✅ UUID tipe string

    protected $fillable = [
        'company_id',
        'role_id',
        'full_name',
        'email',
        'phone_number',
        'password',
        'is_verified',
    ];

    protected static function boot()
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

