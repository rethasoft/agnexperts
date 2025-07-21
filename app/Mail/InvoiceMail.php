<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

use function PHPUnit\Framework\fileExists;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS'),
            subject: 'Factuur ' . $this->data->file_id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(view: 'email.invoice');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {

        if ($this->data->paid == 1) {
            $attachments = [];
            $files = $this->data->files->where('table', 'Keuringen');
            if ($files->isNotEmpty()) {
                foreach ($files as $file) {
                    // Construct the full file path
                    $filePath = public_path($file->path . $file->name);
                    // Check if the file exists
                    if (file_exists($filePath)) {
                        // Attach the file
                        $attachments[] = Attachment::fromPath($filePath, $file->filename);
                    }
                }
                return $attachments;
            }
            return [];
        }
        if ($this->data->getInvoiceFile) {
            $attachment = public_path($this->data->getInvoiceFile->path . $this->data->getInvoiceFile->name);
            if (fileExists($attachment)) {
                return [
                    Attachment::fromPath($attachment, 'factuur-' . $this->data->file_id . '.pdf'),
                ];
            }
            return back()->withErrors(['msg' => __('validation.custom.email_attachment_not_exist')]);
        }
        return [];
    }
}
