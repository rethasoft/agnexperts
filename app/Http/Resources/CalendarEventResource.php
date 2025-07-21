<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarEventResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this['id'],
            'title'        => $this['title'], 
            'start_date'   => $this['start_date'],
            'end_date'     => $this['end_date'],
            'employe'      => $this['employe'],
            'status'       => $this['status'],
            'status_color' => $this['status_color'],
            'keuringen_id' => $this['keuringen_id'],
            'file_id'      => $this['file_id'],
            'adres'        => $this['adres'],
        ];
    }
}
