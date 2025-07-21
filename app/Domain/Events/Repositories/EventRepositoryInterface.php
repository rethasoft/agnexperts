<?php

namespace App\Domain\Events\Repositories;

use App\Domain\Events\Models\Event;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

interface EventRepositoryInterface
{
    public function create(array $data): Event;
    
    public function update(Event $event, array $data): Event;
    
    public function delete(Event $event): bool;
    
    public function findById(int $id): ?Event;
    
    public function getEventsBetweenDates(DateTime $startDate, DateTime $endDate): Collection;
    
    public function hasConflict(int $employeeId, Carbon|DateTime $startDate, Carbon|DateTime $endDate, ?int $excludeEventId = null): bool;
    
    public function getEmployeeAvailability(int $employeeId, DateTime $startDate, DateTime $endDate): array;
    
    public function getUpcomingEvents(int $employeeId, int $limit = 5): Collection;
    
    public function getEventStatistics(int $employeeId, DateTime $startDate, DateTime $endDate): array;
} 