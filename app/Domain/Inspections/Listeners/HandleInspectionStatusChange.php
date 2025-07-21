<?php

namespace App\Domain\Inspections\Listeners;

use App\Domain\Inspections\Events\InspectionStatusChanged;
use App\Domain\Inspections\Mail\InspectionStatusChangeMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HandleInspectionStatusChange
{
    public function handle(InspectionStatusChanged $event)
    {
        Log::info('Inspection status changed', [
            'new_status' => $event->newStatus,
            'old_status' => $event->oldStatus
        ]);

        $emailRecipient = $event->inspection->client ?? $event->inspection;

        if ($emailRecipient) {
            try {
                Mail::to($emailRecipient)->send(new InspectionStatusChangeMail($event->inspection));
                Log::info('Status change notification sent successfully');
            } catch (\Exception $e) {
                Log::error('Failed to send status change notification', [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
