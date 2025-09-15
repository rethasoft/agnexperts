<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMoneyFormat;

class Type extends Model
{
    use HasFactory;
    use HasMoneyFormat;

    protected $fillable = ['category_id', 'tenant_id', 'name', 'short_name', 'quantity', 'price', 'extra', 'extra_price', 'regions', 'is_offerte'];

    protected $attributes = [
        'price' => 0,
        'extra' => 0,
        'extra_price' => 0.00,
        'is_offerte' => false
    ];

    protected $casts = [
        'price' => 'float',
        'extra_price' => 'float',
        'extra' => 'boolean',
        'regions' => 'array',
        'is_offerte' => 'boolean'
    ];

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
    

    public function getCategoryName()
    {
        $type = Type::where('id', $this->category_id)->first();
        if ( $type )
            return $type->short_name;
        return ' - ';
    }
    public function subTypes()
    {
        return $this->hasMany(Type::class, 'category_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Type::class, 'category_id', 'id');
    }

    /**
     * Get formatted price
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->getMoneyFormat('price');
    }

    /**
     * Get formatted extra price
     *
     * @return string
     */
    public function getFormattedExtraPriceAttribute(): string
    {
        if (!$this->extra) {
            return '-';
        }
        
        return $this->getMoneyFormat('extra_price');
    }

    // Helper method to get available regions
    public static function getAvailableRegions()
    {
        return [
            'brussel' => 'Brussel',
            'vlaanderen' => 'Vlaanderen'
        ];
    }

    // Helper method to check if type is available in a specific region
    public function isAvailableInRegion($region)
    {
        return in_array($region, $this->regions ?? []);
    }

    /**
     * Check if this type is an offerte service
     *
     * @return bool
     */
    public function isOfferte(): bool
    {
        return $this->is_offerte;
    }

    /**
     * Get display price for offerte services
     *
     * @return string
     */
    public function getDisplayPriceAttribute(): string
    {
        if ($this->is_offerte) {
            return 'Offerte';
        }
        
        return $this->getFormattedPriceAttribute();
    }
}
