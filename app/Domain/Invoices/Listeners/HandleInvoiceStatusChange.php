<?php

namespace App\Domain\Invoices\Listeners;

use App\Domain\Invoices\Events\InvoiceStatusChanged;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Invoices\Notifications\AdminFilesNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Domain\Invoices\Mail\InvoiceFilesMail;

class HandleInvoiceStatusChange
{
    public function handle(InvoiceStatusChanged $event)
    {
        if ($event->newStatus == 'PAID' && $event->oldStatus == 'DRAFT') {

            Log::info('Status changed', [
                'new_status' => $event->newStatus,
                'old_status' => $event->oldStatus
            ]);
            // Admin dosyalarını müşteriye gönder
            $this->sendAdminFilesToCustomer($event->invoice);
        }
    }

    private function sendAdminFilesToCustomer(Invoice $invoice)
    {
        $client = $invoice->inspection->client;
        $emailRecipient = $client ?? $invoice->inspection;

        Log::info('Attempting to send email', [
            'recipient_email' => $emailRecipient->email,
            'recipient_type' => $client ? 'client' : 'inspection',
            'invoice_id' => $invoice->id
        ]);

        if ($emailRecipient) {
            try {
                Mail::to($emailRecipient)->send(new InvoiceFilesMail($invoice));
                Log::info('Email sent successfully');
            } catch (\Exception $e) {
                Log::error('Failed to send email', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }
}
