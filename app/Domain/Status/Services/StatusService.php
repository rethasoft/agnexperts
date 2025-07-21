<?php

namespace App\Domain\Status\Services;

use App\Domain\Status\Repositories\StatusRepositoryInterface;

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