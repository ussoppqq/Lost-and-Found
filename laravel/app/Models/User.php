<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'company_id', 'role_id', 'full_name', 'email', 
        'phone_number', 'password', 'is_verified'
    ];

    protected $hidden = ['password'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id', 'user_id');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'user_id', 'user_id');
    }
}
