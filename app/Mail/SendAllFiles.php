<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class SendAllFiles extends Mailable
{
    use Queueable, SerializesModels;

    public $files;
    public $file_id;

    /**
     * Create a new message instance.
     */
    public function __construct($files, $file_id)
    {
        $this->files = $files;
        $this->file_id = $file_id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: settings()->email,
            subject: 'Project nr: ' . $this->file_id . ' certificaaten',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.send_all_files',
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
        foreach($this->files as $file){
            $path = public_path($file->path . $file->name);
            $attachments[] = Attachment::fromPath($path); // Create an attachment from the file path]
        }   
        return $attachments;
    }
}
