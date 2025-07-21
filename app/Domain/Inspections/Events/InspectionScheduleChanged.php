<?php

namespace App\Domain\Inspections\Events;

class InspectionScheduleChanged
{
    public function __construct(
        public readonly string $oldDate,
        public readonly string $newDate,
        public readonly mixed $inspection
    ) {}
}
