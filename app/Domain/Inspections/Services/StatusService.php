<?php

namespace App\Domain\Inspections\Services;

use App\Domain\Inspections\Repositories\StatusRepositoryInterface;

class StatusService
{
    public function __construct(
        private readonly StatusRepositoryInterface $statusRepository
    ) {}

    public function getAllStatuses()
    {
        return $this->statusRepository->getAllStatuses();
    }
}