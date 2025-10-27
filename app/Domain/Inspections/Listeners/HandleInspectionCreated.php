<?php

namespace App\Domain\Inspections\Listeners;

use App\Domain\Inspections\Events\InspectionCreated;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
// use Illuminate\Contracts\Queue\ShouldQueue;

class HandleInspectionCreated // implements ShouldQueue
{
    public function handle(InspectionCreated $event)
    {
        $inspection = $event->inspection;

        Log::info('HandleInspectionCreated listener triggered', [
            'inspection_id' => $inspection->id,
            'inspection_email' => $inspection->email,
            'inspection_name' => $inspection->name,
            'has_email' => !empty($inspection->email),
            'items_count' => $inspection->items->count()
        ]);

        try {
            if ($inspection->email) {
                Log::info('Preparing to send email', [
                    'inspection_id' => $inspection->id,
                    'email_address' => $inspection->email,
                    'mailer_config' => config('mail.default'),
                    'queue_connection' => config('queue.default')
                ]);

                $mailData = [
                    'name' => $inspection->name,
                    'email' => $inspection->email,
                    'phone' => $inspection->phone,
                    'street' => $inspection->street,
                    'number' => $inspection->number,
                    'postal_code' => $inspection->postal_code,
                    'city' => $inspection->city,
                    'company_name' => $inspection->company_name,
                    'vat_number' => $inspection->vat_number,
                    'selectedServicesValidation' => [
                        'selectedServices' => $inspection->items->map(function ($detail) {
                            return [
                                'id' => $detail->type_id,
                                'category_id' => $detail->category_id,
                                'name' => $detail->name,
                                'quantity' => $detail->quantity,
                                'price' => $detail->price,
                                'total' => $detail->total,
                            ];
                        })->toArray()
                    ]
                ];

                Log::info('Mail data prepared', [
                    'inspection_id' => $inspection->id,
                    'mail_data_keys' => array_keys($mailData),
                    'services_count' => count($mailData['selectedServicesValidation']['selectedServices'])
                ]);

                // Test email gönderimi
                Log::info('Attempting to send email directly', [
                    'inspection_id' => $inspection->id,
                    'email_address' => $inspection->email
                ]);

                // Mail::to($inspection->email)
                //     ->send(new OrderConfirmationMail($mailData));

                Log::info('Email sent successfully', [
                    'inspection_id' => $inspection->id,
                    'email_address' => $inspection->email
                ]);
            } else {
                Log::warning('No email address found for inspection', [
                    'inspection_id' => $inspection->id,
                    'inspection_email' => $inspection->email
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send inspection confirmation email', [
                'inspection_id' => $inspection->id,
                'email_address' => $inspection->email,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString()
            ]);
        }
    }
}
