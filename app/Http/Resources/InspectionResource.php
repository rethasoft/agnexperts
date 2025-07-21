<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InspectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            // Basic information
            'client_id' => $this->client_id,
            'employee_id' => $this->employee_id,
            'file_id' => $this->file_id,
            'status_id' => $this->status_id,

            // Personal/Company details
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company_name' => $this->company_name,
            'vat_number' => $this->vat_number,

            // Main address
            'address' => [
                'street' => $this->street,
                'number' => $this->number,
                'box' => $this->box,
                'floor' => $this->floor,
                'postal_code' => $this->postal_code,
                'city' => $this->city,
                'province' => $this->province,
                'province_id' => $this->province_id,
            ],

            // Billing address
            'billing_address' => $this->when($this->has_billing_address, [
                'street' => $this->billing_street,
                'number' => $this->billing_number,
                'box' => $this->billing_box,
                'postal_code' => $this->billing_postal_code,
                'city' => $this->billing_city,
            ]),

            // Financial information
            'financial' => [
                'total' => $this->formatted_total,
                'tax' => $this->formatted_tax,
                'subtotal' => $this->formatted_subtotal,
                'paid' => $this->paid,
                'payment_status' => $this->payment_status,
            ],

            'source' => [
                'value' => $this->source->value,
                'label' => $this->source->label(),
                'icon' => $this->source->icon(),
            ],
            // Additional information
            'inspection_date' => $this->inspection_date?->format('Y-m-d H:i:s'),
            'text' => $this->text,

            // Relationships
            'client' => new ClientResource($this->whenLoaded('client')),
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'items' => InspectionItemResource::collection($this->whenLoaded('items')),
            'invoice' => $this->whenLoaded('invoice'),
            // Timestamps
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
