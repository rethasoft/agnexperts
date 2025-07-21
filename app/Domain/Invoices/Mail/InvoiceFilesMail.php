<?php

namespace App\Domain\Invoices\Mail;

use App\Domain\Invoices\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Services\LogService;
use Illuminate\Mail\SentMessage;

class InvoiceFilesMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly Invoice $invoice,
        private ?LogService $logService = null
    ) {
        $this->logService = $logService ?? app(LogService::class);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        try {
            $envelope = new Envelope(
                from: new Address(config('mail.from.address'), config('company.name') . ' | Documenten'),
                subject: 'Factuur Bestanden - #' . $this->invoice->number,
            );

            // Mail gönderim denemesini logla
            $this->logService->logEmail(
                'send_attempt',
                [
                    'recipient' => $this->invoice->email,
                    'subject' => $envelope->subject,
                    'content' => $this->buildViewData(), // Mail içeriğindeki tüm değişkenler
                    'attachments' => collect($this->attachments)->map(fn($attachment) => [
                        'file_name' => $attachment->file->getClientOriginalName(),
                        'size' => $attachment->file->getSize(),
                        'mime_type' => $attachment->file->getMimeType(),
                    ])->toArray(),
                    'cc' => $this->cc ?? [],
                    'bcc' => $this->bcc ?? [],
                ],
                $this->invoice, // İlişkili model olarak invoice'ı geçiyoruz
                'success',
                'Email sending initiated'
            );

            return $envelope;
        } catch (\Exception $e) {
            // Hata durumunu logla
            $this->logService->logEmail(
                'send_failed',
                [
                    'recipient' => $this->invoice->email,
                    'subject' => 'Factuur Bestanden - #' . $this->invoice->number,
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'content' => $this->buildViewData(),
                    'attachments' => collect($this->attachments ?? [])->map(fn($attachment) => [
                        'file_name' => $attachment->file->getClientOriginalName(),
                        'size' => $attachment->file->getSize(),
                        'mime_type' => $attachment->file->getMimeType(),
                    ])->toArray(),
                ],
                $this->invoice,
                'error',
                $e->getMessage()
            );

            throw $e;
        }
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.invoice_files_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->invoice->inspection->adminFiles as $file) {
            if ($file->path) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath(
                    storage_path('app/' . $file->path)
                )->as($file->original_name ?? $file->name);
            }
        }

        return $attachments;
    }

    /**
     * Mail gönderimi tamamlandığında çağrılacak
     */
    public function sent(SentMessage $message): void
    {
        $this->logService->logEmail(
            'sent_successfully',
            [
                'recipient' => $this->invoice->email,
                'message_id' => $message->getMessageId(),
                'sent_at' => now()->toDateTimeString(),
            ],
            $this->invoice,
            'success',
            'Email sent successfully'
        );
    }
}
