<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'short_description', 'description'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (Auth::check()) {
                $service->tenant_id = Auth::id();
            }
        });

        static::addGlobalScope('guard', function ($builder) {
            if (auth()->check() && auth()->user()->type == 'tenant') {
                $builder->where('tenant_id', auth()->user()->id);
            }
        });
    }

    // Automatically generate slug when setting name
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
