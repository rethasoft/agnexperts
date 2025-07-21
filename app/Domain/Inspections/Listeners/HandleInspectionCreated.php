<?php

namespace App\Domain\Inspections\Listeners;

use App\Domain\Inspections\Events\InspectionCreated;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleInspectionCreated implements ShouldQueue
{
    public function handle(InspectionCreated $event)
    {
        $inspection = $event->inspection;

        try {
            if ($inspection->email) {
                $mailData = [
                    'name' => $inspection->name,
                    'email' => $inspection->email,
                    'phone' => $inspection->phone,
                    'street' => $inspection->street,
                    'number' => $inspection->number,
                    'postal_code' => $inspection->postal_code,
                    'city' => $inspection->city,
                    'company_name' => $inspection->company_name,
                    'vat_number' => $inspection->vat_number,
                    'selectedServicesValidation' => [
                        'selectedServices' => $inspection->items->map(function ($detail) {
                            return [
                                'id' => $detail->type_id,
                                'category_id' => $detail->category_id,
                                'name' => $detail->name,
                                'quantity' => $detail->quantity,
                                'price' => $detail->price,
                                'total' => $detail->total,
                            ];
                        })->toArray()
                    ]
                ];

                Mail::to($inspection->email)
                    ->queue(new OrderConfirmationMail($mailData));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send inspection confirmation email', [
                'inspection_id' => $inspection->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
