<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class StatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $keuringen, $newStatus;
    /**
     * Create a new message instance.
     */
    public function __construct($keuringen, $newStatus)
    {
        $this->keuringen = $keuringen;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('gokhan@rethasoft.com', 'Gökhan'),
            subject: 'Dossiernummer: '.$this->keuringen->file_id.' status gewijzigd',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.template',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
