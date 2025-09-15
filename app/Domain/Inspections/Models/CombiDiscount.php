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

    public function getServiceNamesAttribute()
    {
        if (!$this->service_ids || !is_array($this->service_ids)) return '';
        $types = \App\Models\Type::whereIn('id', $this->service_ids)->get();
        $names = $types->map(function($type) {
            if ($type->category_id && $type->category_id != 0) {
                $category = $type->category; // belongsTo ile ana kategori
                return ($category ? $category->name . ' > ' : '') . $type->name;
            } else {
                return $type->name;
            }
        });
        return $names->implode(' + ');
    }
} 