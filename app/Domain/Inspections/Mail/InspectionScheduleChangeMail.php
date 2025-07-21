<?php

namespace App\Domain\Inspections\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Domain\Inspections\Models\Inspection;

class InspectionScheduleChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly Inspection $inspection,
        private readonly string $oldDate,
        private readonly string $newDate
    ) {}

    public function build()
    {
        return $this->view('emails.inspections.schedule-changed')
            ->subject('Inspection Schedule Update')
            ->with([
                'inspection' => $this->inspection,
                'oldDate' => $this->oldDate,
                'newDate' => $this->newDate,
                'clientName' => $this->inspection->client->name ?? 'Client',
                'fileId' => $this->inspection->file_id
            ]);
    }
}
