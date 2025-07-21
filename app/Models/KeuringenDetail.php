<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuringenDetail extends Model
{
    use HasFactory;
    protected $fillable = ['keuringen_id', 'category_id', 'type_id', 'name', 'quantity', 'price', 'total'];
    
    public function category()
    {
        return $this->hasOne(Type::class, 'id', 'category_id');
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
