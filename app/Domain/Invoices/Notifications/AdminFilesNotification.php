<?php

namespace App\Domain\Invoices\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminFilesNotification extends Notification
{
    public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        try {
            $mailData = [
                'invoice_id' => $this->invoice->id,
                'greeting' => 'Hello!',
                'content' => 'This is an automated notification about your invoice files.',
                'status' => 'Active'
            ];

            Mail::send('emails.invoice-notification', $mailData, function($message) use ($notifiable) {
                $message->to($notifiable->email)
                       ->subject('Invoice Files Notification - ID: ' . $this->invoice->id);
            });

            if (Mail::failures()) {
                Log::error('Mail sending failed for:', [
                    'email' => $notifiable->email,
                    'invoice_id' => $this->invoice->id
                ]);
                return false;
            }

            Log::info('Mail sent successfully to:', [
                'email' => $notifiable->email,
                'invoice_id' => $this->invoice->id
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error in mail sending process:', [
                'email' => $notifiable->email,
                'invoice_id' => $this->invoice->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
