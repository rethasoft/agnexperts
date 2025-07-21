<?php

namespace App\Domain\Events\DTOs;

use Spatie\LaravelData\DataCollection;

class EventDataCollection extends DataCollection
{
    public static function create(mixed $items): static
    {
        return new static(EventData::class, $items);
    }
} 