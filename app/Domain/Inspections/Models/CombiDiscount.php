<?php

namespace App\Domain\Inspections\Models;

use Illuminate\Database\Eloquent\Model;

class CombiDiscount extends Model
{
    protected $table = 'combi_discounts';

    protected $fillable = [
        'service_ids',
        'discount_type',
        'discount_value',
        'active',
    ];

    protected $casts = [
        'service_ids' => 'array',
        'discount_value' => 'float',
        'active' => 'boolean',
    ];
} 