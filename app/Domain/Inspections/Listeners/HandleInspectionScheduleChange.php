<?php

namespace App\Domain\Inspections\Listeners;

use App\Domain\Inspections\Events\InspectionScheduleChanged;
use App\Domain\Inspections\Mail\InspectionScheduleChangeMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HandleInspectionScheduleChange
{
    public function handle(InspectionScheduleChanged $event)
    {
        Log::info('Inspection schedule changed', [
            'new_date' => $event->newDate,
            'old_date' => $event->oldDate
        ]);

        $emailRecipient = $event->inspection->client ?? $event->inspection;

        if ($emailRecipient) {
            try {
                Mail::to($emailRecipient)->send(new InspectionScheduleChangeMail(
                    inspection: $event->inspection,
                    oldDate: $event->oldDate,
                    newDate: $event->newDate
                ));
                Log::info('Schedule change notification sent successfully');
            } catch (\Exception $e) {
                Log::error('Failed to send schedule change notification', [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
