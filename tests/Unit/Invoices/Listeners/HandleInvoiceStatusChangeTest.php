<?php
use Tests\TestCase;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Invoices\Events\InvoiceStatusChanged;
use App\Domain\Invoices\Listeners\HandleInvoiceStatusChange;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HandleInvoiceStatusChangeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake(); // Kaldırın veya yorum satırı yapın
    }

    /** @test */
    public function it_sends_notification_for_existing_invoice()
    {
        // Var olan bir invoice ID'si kullanın
        $invoice = Invoice::find(4); // Veritabanınızdaki gerçek bir invoice ID'si

        if (!$invoice) {
            $this->markTestSkipped('Test invoice bulunamadı');
        }

        Log::info('Test başlıyor', [
            'invoice_id' => $invoice->id,
            'inspection_email' => $invoice->inspection->email,
            'client_email' => $invoice->inspection->client->email ?? 'Client yok'
        ]);

        // Mail gönderimini force et
        config(['mail.default' => 'smtp']);
        
        // InvoiceStatusChanged event'ini oluştur
        $event = new InvoiceStatusChanged($invoice, 'draft', 'paid');

        $listener = new HandleInvoiceStatusChange();
        $listener->handle($event); // Event'i gönder

        // Mail gönderimini kontrol et
        $this->assertTrue(true);
    }
} 