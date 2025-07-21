<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            
            // Personal details
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            
            // Work details
            'position' => $this->position,
            'department' => $this->department,
            'employee_number' => $this->employee_number,
            
            // Status
            'status' => $this->status,
            'is_active' => $this->is_active,
            
            // Timestamps
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            
            // İlişkiler (ihtiyaca göre)
            'inspections_count' => $this->when(
                $this->inspections_count !== null,
                $this->inspections_count
            ),
        ];
    }
} 