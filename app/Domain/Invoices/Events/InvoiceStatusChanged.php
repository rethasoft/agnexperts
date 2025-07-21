<?php

namespace App\Domain\Invoices\Events;

use App\Domain\Invoices\Models\Invoice;

class InvoiceStatusChanged
{
    public function __construct(
        public readonly Invoice $invoice,
        public readonly string $oldStatus,
        public readonly string $newStatus
    ) {}
}
