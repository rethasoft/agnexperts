<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InspectionItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'inspection_id' => $this->inspection_id,
            'category_id' => $this->category_id,
            'type_id' => $this->type_id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            // İlişkiler
            'category' => new CategoryResource($this->whenLoaded('category')),
            'type' => new TypeResource($this->whenLoaded('type')),
        ];
    }
} 