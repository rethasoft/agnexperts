<?php

namespace App\Domain\Inspections\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

class InspectionException extends DomainException
{
    protected function getDomain(): string
    {
        return 'inspection';
    }
} 