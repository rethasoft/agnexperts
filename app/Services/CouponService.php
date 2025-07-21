<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Keuringen;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class CouponService
{
    public function validateCoupon(string $code, float $orderAmount, User $user): ?Coupon
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            throw new Exception('Invalid coupon code.');
        }

        if (!$coupon->isValid()) {
            throw new Exception('This coupon is no longer valid.');
        }

        if ($coupon->min_order_amount && $orderAmount < $coupon->min_order_amount) {
            throw new Exception("Minimum order amount of {$coupon->min_order_amount} required.");
        }

        if ($coupon->usages()->where('user_id', $user->id)->exists()) {
            throw new Exception('You have already used this coupon.');
        }

        return $coupon;
    }

    public function calculateDiscount(Coupon $coupon, float $orderAmount): float
    {
        if ($coupon->discount_type === 'percentage') {
            return ($orderAmount * $coupon->discount_value) / 100;
        }

        return min($coupon->discount_value, $orderAmount);
    }

    public function applyCoupon(Coupon $coupon, Keuringen $keuringen, User $user): void
    {
        $discountAmount = $this->calculateDiscount($coupon, $keuringen->total_amount);

        $coupon->usages()->create([
            'user_id' => $user->id,
            'order_id' => $keuringen->id,
            'discount_amount' => $discountAmount,
        ]);

        $coupon->increment('used_count');
        
        // Update order total
        $keuringen->update([
            'discount_amount' => $discountAmount,
            'final_amount' => $keuringen->total_amount - $discountAmount,
        ]);
    }
} 