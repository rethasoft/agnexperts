<?php

namespace App\Domain\Inspections\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Domain\Inspections\Models\Inspection;

class InspectionStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly Inspection $inspection
    ) {}

    public function build()
    {
        return $this->view('emails.inspections.status-changed')
            ->subject('Inspection Status Update')
            ->with([
                'inspection' => $this->inspection,
                'status' => $this->inspection->status,
                'clientName' => $this->inspection->client->name ?? 'Client',
                'inspectionDate' => $this->inspection->inspection_date,
                'fileId' => $this->inspection->file_id
            ]);
    }
}
