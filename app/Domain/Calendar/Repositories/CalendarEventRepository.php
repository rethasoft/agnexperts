<?php

namespace App\Domain\Calendar\Repositories;

use App\Domain\Inspections\Models\Inspection;
use Illuminate\Support\Collection;

class CalendarEventRepository
{
    public function getEventsBetweenDates(string $startDate, string $endDate): Collection
    {
        return Inspection::query()
            ->with(['employee', 'status', 'client'])
            ->forUser()
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->get()
            ->map(function ($inspection) {
                return [
                    'id' => $inspection->id,
                    'title' => $inspection->client->name,
                    'start_date' => $inspection->scheduled_at,
                    'end_date' => $inspection->scheduled_end_at,
                    'employe' => $inspection->employee?->name,
                    'status' => $inspection->status?->name,
                    'status_color' => $inspection->status?->color_class,
                    'keuringen_id' => $inspection->id,
                    'file_id' => $inspection->file_id,
                    'adres' => $inspection->address
                ];
            });
    }
} 