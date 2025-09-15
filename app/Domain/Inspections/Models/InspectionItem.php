<?php

namespace App\Domain\Inspections\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Type;

class InspectionItem extends Model
{
    protected $table = 'inspection_items';

    protected $fillable = [
        'inspection_id',
        'category_id',
        'type_id',
        'name',
        'quantity',
        'price',
        'total',
        'is_offerte',
    ];

    protected $casts = [
        'is_offerte' => 'boolean',
        'price' => 'float',
        'total' => 'float',
        'quantity' => 'integer',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
    
    public function category()
    {
        return $this->hasOne(Type::class, 'id', 'category_id');
    }
    
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    // Helper method to check if this item is an offerte
    public function isOfferte(): bool
    {
        return $this->is_offerte;
    }

    // Get display price - shows "Offerte" if is_offerte is true
    public function getDisplayPriceAttribute(): string
    {
        if ($this->is_offerte) {
            return 'Offerte';
        }
        return '€' . number_format($this->price, 2, ',', '.');
    }

    // Get display total - shows "Offerte" if is_offerte is true
    public function getDisplayTotalAttribute(): string
    {
        if ($this->is_offerte) {
            return 'Offerte';
        }
        return '€' . number_format($this->total, 2, ',', '.');
    }
}
