<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // ✅ import this

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids; // ✅ use HasUuids

    /**
     * Use UUID instead of auto-incrementing integer
     */
    public $incrementing = false;       // ✅ stop auto-incrementing IDs
    protected $keyType = 'string';      // ✅ primary key is a string (UUID)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'studentid',      // ✅ add this if you’re setting UUID manually
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
