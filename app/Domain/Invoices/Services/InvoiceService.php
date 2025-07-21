<?php

namespace App\Domain\Invoices\Services;

use App\Domain\Invoices\DTOs\InvoiceData;
use App\Domain\Invoices\Actions\CreateInvoiceAction;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Invoices\Events\InvoiceStatusChanged;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    public function __construct(
        private readonly CreateInvoiceAction $createInvoiceAction
    ) {}

    public function createFromInspection($inspection): Invoice
    {
        $data = InvoiceData::fromInspection($inspection);
        return $this->createInvoiceAction->execute($data);
    }

    public function syncWithBillit(Invoice $invoice)
    {
        // Billit entegrasyonu için hazır method
    }

    public function updateStatus(Invoice $invoice, string $newStatus): Invoice
    {
        try {
            // Eski durumu kaydet
            $oldStatus = $invoice->status;

            // Durumu güncelle
            $invoice->update(['status' => $newStatus]);


            // Event'i tetikle
            event(new InvoiceStatusChanged($invoice, $oldStatus->value, $newStatus));

            return $invoice->fresh();
        } catch (\Exception $e) {
            throw new \Exception('Fout bij het bijwerken van de factuurstatus: ' . $e->getMessage());
        }
    }
}
