<?php

namespace App\Domain\Inspections\Repositories;

use App\Domain\Inspections\Models\CombiDiscount;

class CombiDiscountRepository
{
    public function all()
    {
        return CombiDiscount::all();
    }

    public function find($id)
    {
        return CombiDiscount::find($id);
    }

    public function create(array $data)
    {
        return CombiDiscount::create($data);
    }

    public function update($id, array $data)
    {
        $combi = CombiDiscount::findOrFail($id);
        $combi->update($data);
        return $combi;
    }

    public function delete($id)
    {
        $combi = CombiDiscount::findOrFail($id);
        return $combi->delete();
    }
} 