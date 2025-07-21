<?php

namespace App\Domain\Events\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Events\Enums\EventStatus;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Event tipine göre renk belirleme
        $colors = [
            'inspection' => [
                'backgroundColor' => '#4CAF50',
                'borderColor' => '#45a049',
                'textColor' => '#FFFFFF'
            ],
            'leave' => [
                'backgroundColor' => '#FFA726',
                'borderColor' => '#FB8C00',
                'textColor' => '#FFFFFF'
            ],
            'sick' => [
                'backgroundColor' => '#EF5350',
                'borderColor' => '#E53935',
                'textColor' => '#FFFFFF'
            ]
        ];

        $eventColors = $colors[$this->type->value] ?? [
            'backgroundColor' => '#2196F3',
            'borderColor' => '#1E88E5',
            'textColor' => '#FFFFFF'
        ];

        /** @var EventStatus $status */
        $status = EventStatus::tryFrom($this->status);

        return [
            'id' => $this->id,
            'title' => $this->whenLoaded('employee', function () {
                return sprintf(
                    '%s - %s',
                    $this->title ?? $this->type->value,
                    $this->employee->name
                );
            }, $this->title ?? $this->type->value),
            'start' => $this->start_date->format('Y-m-d\TH:i:s'),
            'end' => $this->end_date->format('Y-m-d\TH:i:s'),
            'type' => $this->type->value,
            'description' => $this->description,
            'status' => [
                'value' => $this->status,
                'label' => $status ? $status->label() : $this->status,
                'color' => $status ? $status->color() : 'secondary',
                'icon' => $status ? $status->icon() : 'ri-question-line'
            ],
            'is_available' => $this->is_available,
            'is_all_day' => $this->is_all_day,
            // Calendar için gerekli renkler
            'backgroundColor' => $eventColors['backgroundColor'],
            'borderColor' => $eventColors['borderColor'],
            'textColor' => $eventColors['textColor'],
            // Ek bilgiler
            'meta' => $this->meta,
            'inspection' => $this->whenLoaded('inspection', function () {
                return [
                    'id' => $this->inspection->id,
                    'file_id' => $this->inspection->file_id,
                    'formatted_address' => $this->inspection->formatted_address,
                    'province' => $this->inspection->province,
                    'company_name' => $this->inspection->company_name,
                    'client_name' => $this->inspection->name,
                ];
            }),
            'employee' => $this->whenLoaded('employee', function () {
                return [
                    'id' => $this->employee->id,
                    'name' => $this->employee->name
                ];
            })
        ];
    }
}
