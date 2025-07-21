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
}
