<?php

namespace App\Domain\Inspections\Observers;

use App\Domain\Inspections\Models\Inspection;
use Illuminate\Support\Facades\Log;

class InspectionObserver
{
    public function saved(Inspection $inspection)
    {
        Log::info("Inspection saved event triggered", [
            'inspection_id' => $inspection->id
        ]);

        $items = $inspection->items()->get();
        
        Log::debug("Items found", [
            'items_count' => $items->count(),
            'items_data' => $items->toArray()
        ]);
        
        $subtotal = $items->sum('total');
        $tax = $subtotal * 0.21; // Vergi oranı
        $total = $subtotal + $tax;

        Log::debug("Calculated values", [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ]);

        // Sonsuz döngüyü önlemek için updateQuietly kullanıyoruz
        $inspection->updateQuietly([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ]);

        Log::info("Inspection totals updated successfully");
    }
} 