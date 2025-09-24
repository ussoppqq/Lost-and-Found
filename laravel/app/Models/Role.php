<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['role_code', 'role_name'];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
