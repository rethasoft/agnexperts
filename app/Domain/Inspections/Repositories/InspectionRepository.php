<?php

namespace App\Domain\Inspections\Repositories;

use App\Domain\Inspections\Models\Inspection;
use Illuminate\Database\Eloquent\Collection;

class InspectionRepository
{
    public function __construct(
        private readonly Inspection $model
    ) {}

    public function create(array $data): Inspection
    {
        return $this->model->create($data);
    }

    public function update(Inspection $inspection, array $data): Inspection
    {
        $inspection->update($data);
        return $inspection->fresh();
    }

    public function findById(int $id): ?Inspection
    {
        return $this->model->find($id);
    }

    public function getByFilters(array $filters): Collection
    {
        return $this->model->query()
            ->with(['client', 'employee', 'status', 'type'])
            ->when(isset($filters['status_id']), function ($query) use ($filters) {
                $query->where('status_id', $filters['status_id']);
            })
            ->when(isset($filters['employee_id']), function ($query) use ($filters) {
                $query->where('employee_id', $filters['employee_id']);
            })
            ->when(isset($filters['client_id']), function ($query) use ($filters) {
                $query->where('client_id', $filters['client_id']);
            })
            ->when(isset($filters['source']), function ($query) use ($filters) {
                $query->where('source', $filters['source']);
            })
            ->orderBy('scheduled_at', 'desc')
            ->get();
    }

    public function filter(array $filters)
    {
        return $this->model
            ->when(isset($filters['source']), function($query) use ($filters) {
                $query->where('source', $filters['source']);
            })
            ->get();
    }
} 