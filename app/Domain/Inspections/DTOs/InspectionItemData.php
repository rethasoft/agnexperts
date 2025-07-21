<?php

namespace App\Domain\Inspections\DTOs;

class InspectionItemData
{
    public function __construct(
        public int $category_id,
        public int $type_id,
        public string $name,
        public int $quantity,
        public float $price,
        public ?float $total = null
    ) {
        $this->total = $this->total ?? ($this->price * $this->quantity);
    }
} 