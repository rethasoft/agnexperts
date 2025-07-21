<?php

namespace App\Domain\Calendar\Actions;

use App\Domain\Calendar\Repositories\CalendarEventRepository;
use Illuminate\Support\Collection;

class FetchCalendarEventsAction
{
    public function __construct(
        private readonly CalendarEventRepository $repository
    ) {}

    public function execute(?string $startDate = null, ?string $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->startOfMonth()->toDateString();
        $endDate = $endDate ?? now()->endOfMonth()->toDateString();

        return $this->repository->getEventsBetweenDates($startDate, $endDate);
    }
} 