<?php

namespace App\Domain\Inspections\Events;

use App\Domain\Inspections\Models\Inspection;
use Illuminate\Foundation\Events\Dispatchable;

class InspectionCreated
{
    use Dispatchable;

    public function __construct(public Inspection $inspection)
    {}
} 