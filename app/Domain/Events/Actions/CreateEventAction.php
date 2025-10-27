<?php

namespace App\Domain\Events\Actions;

use App\Domain\Events\DTOs\EventData;
use App\Domain\Events\Models\Event;
use App\Domain\Events\Repositories\EventRepository;
use App\Domain\Events\Exceptions\ConflictingEventException;

class CreateEventAction
{
    public function __construct(
        private readonly EventRepository $repository
    ) {}

    public function execute(EventData $data): Event
    {
        // İş çakışması kontrolü (GEÇİCİ OLARAK DEVRE DIŞI)
        // if ($this->repository->hasConflict($data->employee_id, $data->start_date, $data->end_date)) {
        //     throw new ConflictingEventException();
        // }

        return $this->repository->create($data->toArray());
    }
} 