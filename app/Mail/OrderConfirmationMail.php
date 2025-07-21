<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmationMail extends Mailable implements ShouldQueue
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
        return $this->from('info@agnexperts.be', 'AGN Experts')
                    ->subject('Bestelbevestiging - Uw bestelling is ontvangen')
                    ->view('order::emails.order_confirmation')
                    ->with([
                        'orderDetails' => $this->orderDetails,
                        'selectedServicesValidation' => $this->selectedServicesValidation
                    ]);
    }
}