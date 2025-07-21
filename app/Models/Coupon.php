<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'max_uses',
        'used_count',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid(): bool
    {
        return $this->is_active &&
            (!$this->starts_at || $this->starts_at->isPast()) &&
            (!$this->expires_at || $this->expires_at->isFuture()) &&
            (!$this->max_uses || $this->used_count < $this->max_uses);
    }
}
