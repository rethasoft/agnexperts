<?php

namespace App\Domain\Inspections\Exceptions;

class InspectionNotFoundException extends InspectionException
{
    public function __construct(int $id)
    {
        parent::__construct(
            "Inspection #{$id} not found",
            [],
            404
        );
    }
} 