<?php

namespace App\Http\Controllers;

use App\Domain\Invoices\Services\InvoiceService;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Invoices\Enums\InvoiceStatus;
use App\Models\File;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    use FileUploadTrait;

    public function __construct(
        private readonly InvoiceService $invoiceService
    ) {}

    public function store(Request $request)
    {
        $inspection = $request->inspection;

        // Fatura oluştur
        $invoice = $this->invoiceService->createFromInspection($inspection);

        // Dosya yükleme varsa
        if ($request->hasFile('invoice')) {
            $files = $this->uploadFiles(
                [$request->file('invoice')],
                'invoice',
                $inspection->id,
                'inspections'
            );

            // İlk dosyayı invoice ile ilişkilendir
            if ($files->isNotEmpty()) {
                $invoice->update(['file_id' => $files->first()->id]);
            }
        }

        return response()->json([
            'message' => 'Invoice created successfully',
            'invoice' => $invoice->load('file')
        ]);
    }

    public function updateStatus(Invoice $invoice)
    {
        try {
            $this->invoiceService->updateStatus($invoice, InvoiceStatus::PAID->value);
            return back()->with('success', 'Factuurstatus succesvol bijgewerkt');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Er is een fout opgetreden: ' . $e->getMessage()]);
        }
    }

    public function show(Invoice $invoice)
    {
        return response()->json($invoice->load('inspection', 'file'));
    }
}
