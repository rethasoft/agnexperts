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
        'company_name',
        'btw_number',
        'email',
        'password',
        'phone',
        'street',
        'house_number',
        'house_number_addition',
        'postal_code',
        'city',
        'contact_person',
        'industry',
        'address',
        'internal_notes',
        'client_notes',
        'billing_address'
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
        'password' => "hashed",
        'billing_address' => 'array'
    ];

    public function inspections()
    {
        return $this->hasMany(\App\Domain\Inspections\Models\Inspection::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
