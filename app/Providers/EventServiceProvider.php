<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Domain\Invoices\Events\InvoiceStatusChanged::class => [
            \App\Domain\Invoices\Listeners\HandleInvoiceStatusChange::class,
        ],
        \App\Domain\Inspections\Events\InspectionCreated::class => [
            \App\Domain\Inspections\Listeners\HandleInspectionCreated::class,
        ],
        \App\Domain\Inspections\Events\InspectionStatusChanged::class => [
            \App\Domain\Inspections\Listeners\HandleInspectionStatusChange::class,
        ],
        \App\Domain\Inspections\Events\InspectionScheduleChanged::class => [
            \App\Domain\Inspections\Listeners\HandleInspectionScheduleChange::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
