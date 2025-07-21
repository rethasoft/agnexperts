<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            
            // Personal/Company details
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company_name' => $this->company_name,
            'vat_number' => $this->vat_number,
            
            // Address
            'address' => [
                'street' => $this->street,
                'number' => $this->number,
                'box' => $this->box,
                'postal_code' => $this->postal_code,
                'city' => $this->city,
                'province' => $this->province,
            ],
            
            // Additional info
            'status' => $this->status,
            'notes' => $this->notes,
            
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