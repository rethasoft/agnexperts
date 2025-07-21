<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Status extends Model
{
    use HasFactory;
    protected $fillable = ['tenant_id', 'name', 'color'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('guard', function ($builder) {
            if (auth()->guard('client')->check()) {
                $client = auth()->guard('client')->user()->client;
                $builder->where('tenant_id', $client->tenant_id);
            } elseif (auth()->guard('tenant')->check()) {
                $tenant = auth()->guard('tenant')->user();
                $builder->where('tenant_id', $tenant->id);
            }
        });
    }

}
