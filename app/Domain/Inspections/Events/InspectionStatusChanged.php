<?php

namespace App\Domain\Inspections\Events;

class InspectionStatusChanged
{
    public function __construct(
        public readonly string $oldStatus,
        public readonly string $newStatus,
        public readonly mixed $inspection
    ) {}
}
