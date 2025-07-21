<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'user_id',
        'name',
        'surname',
        'email',
        'password',
        'phone',
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization
     * 
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     *  The attributes that should be cast
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'password' => "hashed"
    ];

    public function keuringens()
    {
        return $this->hasMany(Keuringen::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
