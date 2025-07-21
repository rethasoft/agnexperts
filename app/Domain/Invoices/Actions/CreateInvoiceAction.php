<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\Invoices\DTOs\InvoiceData;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Invoices\Events\InvoiceCreated;

class CreateInvoiceAction
{
    public function execute(InvoiceData $data): Invoice
    {
        $invoice = Invoice::create($data->toArray());
        
        // event(new InvoiceCreated($invoice));
        
        return $invoice;
    }
} 