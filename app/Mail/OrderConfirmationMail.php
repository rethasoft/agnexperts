<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use App\Mail\BaseMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
// use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmationMail extends BaseMail // implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $orderDetails;
    public $selectedServicesValidation;
    
    /**
     * Create a new message instance.
     *
     * @param array $mailData
     */
    public function __construct($mailData)
    {
        parent::__construct();
        $this->orderDetails = $mailData;
        $this->selectedServicesValidation = $mailData['selectedServicesValidation'];
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            $mail = $this->subject('Bestelbevestiging - Uw bestelling is ontvangen')
                        ->view('emails.order_confirmation')
                        ->with([
                            'orderDetails' => $this->orderDetails,
                            'selectedServicesValidation' => $this->selectedServicesValidation
                        ]);

            return $mail;
        } catch (\Exception $e) {
            Log::error('OrderConfirmationMail build failed', [
                'error' => $e->getMessage(),
                'email' => $this->orderDetails['email'] ?? 'unknown'
            ]);
            throw $e;
        }
    }
}