<?php

namespace App\Domain\Events\Enums;

enum EventType: string
{
    case STANDARD = 'standard';
    case VACATION = 'vacation';
    case SICK_LEAVE = 'sick_leave';
    case MEETING = 'meeting';
    case PERSONAL = 'personal';
    case INSPECTION = 'inspection';
    case TRAINING = 'training';

    public function color(): string
    {
        return match($this) {
            self::INSPECTION => '#007bff',
            self::VACATION => '#28a745',
            self::SICK_LEAVE => '#dc3545',
            self::MEETING => '#ffc107',
            self::PERSONAL => '#6c757d',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::INSPECTION => 'Inspectie',
            self::VACATION => 'Vakantie',
            self::SICK_LEAVE => 'Ziekte',
            self::MEETING => 'Vergadering',
            self::PERSONAL => 'Persoonlijk',
        };
    }
} 