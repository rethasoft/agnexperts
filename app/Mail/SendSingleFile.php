<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class SendSingleFile extends Mailable
{
    use Queueable, SerializesModels;

    public $filePath;
    public $file_id;

    /**
     * Create a new message instance.
     */
    public function __construct($filePath, $file_id)
    {
        $this->filePath = $filePath;
        $this->file_id = $file_id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(settings()->email, settings()->company),
            subject: 'Project nr: '.$this->file_id .' certificaat',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.send_single_file',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->filePath), // Create an attachment from the file path
        ];
    }
}
