<?php

namespace App\Domain\Events\Actions;

use App\Domain\Events\DTOs\EventData;
use App\Domain\Events\Repositories\EventRepository;
use App\Domain\Events\Models\Event;

class UpdateEventAction
{
    public function __construct(
        private readonly EventRepository $repository
    ) {}

    public function execute(Event $event, EventData $data): Event
    {
        // İş çakışması kontrolü (kendi event'i hariç diğerleriyle çakışma kontrolü)
        if ($this->repository->hasConflict(
            $data->employee_id,
            $data->start_date,
            $data->end_date,
            $event->id // Mevcut event'i hariç tutmak için ID'sini gönderiyoruz
        )) {
            throw new \Exception('Employee has a conflicting event during this time period.');
        }

        return $this->repository->update($event, $data->toArray());
    }
}
