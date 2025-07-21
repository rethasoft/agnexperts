<?php

namespace App\Domain\Events\Repositories;

use App\Domain\Events\Models\Event;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventRepository implements EventRepositoryInterface
{
    public function __construct(
        private readonly Event $model
    ) {}

    public function create(array $data): Event
    {
        return $this->model->create($data);
    }

    public function update(Event $event, array $data): Event
    {
        $event->update($data);
        return $event->fresh();
    }

    public function delete(Event $event): bool
    {
        return $event->delete();
    }

    public function findById(int $id): ?Event
    {
        return $this->model->find($id);
    }

    public function getEventsBetweenDates(DateTime $startDate, DateTime $endDate): Collection
    {
        // Gelen tarihleri UTC'ye çevirelim
        $startDate = $startDate->setTimezone(new \DateTimeZone('UTC'));
        $endDate = $endDate->setTimezone(new \DateTimeZone('UTC'));

        return $this->model->query()
            ->with(['eventable', 'employee'])
            ->whereBetween('start_date', [
                $startDate->format('Y-m-d H:i:s'),
                $endDate->format('Y-m-d H:i:s')
            ])
            ->orderBy('start_date')
            ->get();
    }

    public function hasConflict(int $employeeId, Carbon|DateTime $startDate, Carbon|DateTime $endDate, ?int $excludeEventId = null): bool
    {
        $query = Event::query()
            ->where('employee_id', $employeeId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        if ($excludeEventId) {
            $query->where('id', '!=', $excludeEventId);
        }

        return $query->exists();
    }

    public function getEmployeeAvailability(int $employeeId, DateTime $startDate, DateTime $endDate): array
    {
        $events = $this->model->query()
            ->where('employee_id', $employeeId)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date')
            ->get();

        $busySlots = [];
        foreach ($events as $event) {
            $busySlots[] = [
                'start' => $event->start_date,
                'end' => $event->end_date,
                'type' => $event->type->value,
                'title' => $event->title ?? $event->type->value
            ];
        }

        return $busySlots;
    }

    public function getUpcomingEvents(int $employeeId, int $limit = 5): Collection
    {
        return $this->model->query()
            ->with(['eventable', 'employee'])
            ->where('employee_id', $employeeId)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit($limit)
            ->get();
    }

    public function getEventStatistics(int $employeeId, DateTime $startDate, DateTime $endDate): array
    {
        return DB::table('events')
            ->select(
                'type',
                DB::raw('COUNT(*) as total_events'),
                DB::raw('SUM(TIMESTAMPDIFF(MINUTE, start_date, end_date)) as total_minutes')
            )
            ->where('employee_id', $employeeId)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => [
                    'count' => $item->total_events,
                    'duration' => $item->total_minutes
                ]];
            })
            ->toArray();
    }
}
