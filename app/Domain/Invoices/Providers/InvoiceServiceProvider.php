<?php

namespace App\Domain\Invoices\Providers;

use Illuminate\Support\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(
            app_path('Domain/Invoices/Views'), 
            'Domain.Invoices'
        );
    }

    public function register()
    {
        //
    }
} 