<?php

namespace App\Listeners;

use App\Events\StatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notify;

class SendStatusChangedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StatusChanged $event): void
    {
        Mail::to($event->client->email)->send(new Notify($event->client, $event->newStatus));
    }
}
