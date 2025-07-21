<?php

namespace App\Domain\Inspections\Exceptions;

class InspectionValidationException extends InspectionException
{
    public function __construct(array $errors)
    {
        parent::__construct(
            'Inspection validation failed',
            $errors,
            422
        );
    }
} 