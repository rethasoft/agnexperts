<?php

namespace App\Domain\Inspections\Observers;

use App\Domain\Inspections\Models\InspectionItem;
use Illuminate\Support\Facades\Log;

class InspectionItemObserver
{
    public function saved(InspectionItem $item)
    {
        Log::info("InspectionItem saved event triggered", [
            'item_id' => $item->id,
            'inspection_id' => $item->inspection_id
        ]);

        $inspection = $item->inspection;
        $inspection->load('items');

        // Önce toplam (KDV dahil) fiyatı alalım
        $total = $inspection->items->sum('total');
        
        // KDV oranı 0.21 olduğu için, KDV'siz fiyatı bulmak için:
        // total = sub_total + (sub_total * 0.21)
        // total = sub_total * (1 + 0.21)
        // total = sub_total * 1.21
        // sub_total = total / 1.21
        $sub_total = $total / 1.21;
        $tax = $total - $sub_total;
        $inspection->updateQuietly([
            'total' => $total,
            'sub_total' => $sub_total,
            'tax' => $tax
        ]);

        Log::info("Inspection totals updated successfully");
    }
} 