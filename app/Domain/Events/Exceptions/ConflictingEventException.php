<?php

namespace App\Domain\Events\Exceptions;

use Exception;

class ConflictingEventException extends Exception
{
    public function __construct(string $message = "Employee has a conflicting event during this time period.")
    {
        parent::__construct($message);
    }
} 