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
        'user_id',
        'company_id', // Only for admin/moderator
        'role_id',
        'full_name',
        'nickname',
        'email',
        'email_verification_code',
        'email_verification_code_expires_at',
        'email_verified_at',
        'phone_number',
        'password',
        'is_verified',
        'phone_verified_at',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'phone_verified_at' => 'datetime',
        'email_verification_code_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
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

    /**
     * Check if phone is verified
     */
    public function hasVerifiedPhone(): bool
    {
        return $this->is_verified && $this->phone_verified_at !== null;
    }

    /**
     * Mark phone as verified
     */
    public function markPhoneAsVerified(): bool
    {
        return $this->update([
            'is_verified' => true,
            'phone_verified_at' => now(),
        ]);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role?->role_code === 'ADMIN';
    }

    /**
     * Check if user is moderator
     */
    public function isModerator(): bool
    {
        return $this->role?->role_code === 'MODERATOR';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role?->role_code === 'USER';
    }

    /**
     * Check if user has role
     */
    public function hasRole(string $roleCode): bool
    {
        return $this->role?->role_code === $roleCode;
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}