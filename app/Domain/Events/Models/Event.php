<?php

namespace App\Domain\Events\Models;

use App\Domain\Events\Enums\EventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'eventable_id',
        'eventable_type',
        'employee_id',
        'start_date',
        'end_date',
        'type',
        'description',
        'title',
        'status',
        'is_available',
        'is_all_day',
        'cancellation_reason',
        'cancelled_by',
        'meta'
    ];

    protected $casts = [
        'type' => EventType::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_available' => 'boolean',
        'meta' => 'array',
        'is_all_day' => 'boolean',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';

    public function eventable(): MorphTo
    {
        return $this->morphTo();
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function cancelledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function scopeOverlappingDates($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                });
        });
    }
}
