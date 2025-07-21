<?php

namespace App\Domain\Status\Repositories;

use App\Models\Status;

class StatusRepository implements StatusRepositoryInterface
{
    public function getAllStatuses()
    {
        return Status::all();
    }
}