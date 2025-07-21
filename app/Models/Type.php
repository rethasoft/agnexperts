<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMoneyFormat;

class Type extends Model
{
    use HasFactory;
    use HasMoneyFormat;

    protected $fillable = ['category_id', 'tenant_id', 'name', 'short_name', 'quantity', 'price', 'extra', 'extra_price'];

    protected $attributes = [
        'price' => 0,
        'extra' => 0,
        'extra_price' => 0.00
    ];

    protected $casts = [
        'price' => 'float',
        'extra_price' => 'float',
        'extra' => 'boolean'
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
}
