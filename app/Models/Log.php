<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'event',
        'data',
        'status',
        'message'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    // Yardımcı metodlar
    public static function logEmail(string $event, array $data = [], ?Model $related = null): self
    {
        return self::create([
            'type' => 'email',
            'event' => $event,
            'data' => $data,
            'loggable_type' => $related ? get_class($related) : null,
            'loggable_id' => $related?->id,
        ]);
    }
}
