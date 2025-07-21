<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Database\Eloquent\Model;

class LogService
{
    public function logEmail(
        string $event,
        array $data = [],
        ?Model $related = null,
        ?string $status = 'success',
        ?string $message = null
    ): Log {
        return Log::create([
            'type' => 'email',
            'event' => $event,
            'data' => $data,
            'status' => $status,
            'message' => $message,
            'loggable_type' => $related ? get_class($related) : null,
            'loggable_id' => $related?->id,
        ]);
    }

    public function log(
        string $type,
        string $event,
        array $data = [],
        ?Model $related = null,
        ?string $status = 'success',
        ?string $message = null
    ): Log {
        return Log::create([
            'type' => $type,
            'event' => $event,
            'data' => $data,
            'status' => $status,
            'message' => $message,
            'loggable_type' => $related ? get_class($related) : null,
            'loggable_id' => $related?->id,
        ]);
    }
} 