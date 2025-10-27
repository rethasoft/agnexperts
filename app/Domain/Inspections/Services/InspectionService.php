<?php

namespace App\Domain\Inspections\Services;

use App\Domain\Inspections\Models\Inspection;
use App\Models\Type;
use App\Models\Client;
use App\Models\PriceList;

class InspectionService
{
    public function getTypesForClient($client_id)
    {
        $client = Client::find($client_id);

        if (! $client) {
            return collect();
        }

        // Sadece gerekli kolonları getirerek gereksiz veri taşımayı önle
        $types = Type::query()->where('tenant_id', $client->tenant_id)->get();

        if ($types->isEmpty()) {
            return collect();
        }

        // N+1'i önlemek için ilgili price listelerini tek sorguda çek
        $pricesByTypeId = PriceList::query()
            ->where('client_id', $client->id)
            ->whereIn('type_id', $types->pluck('id'))
            ->pluck('price', 'type_id');

        return $types->map(function ($type) use ($pricesByTypeId) {
            $price = $pricesByTypeId[$type->id] ?? $type->price;
            $type->setAttribute('price', $price);
            return $type;
        });
    }
}
