<?php

namespace App\Modules\Order\Providers;

use App\Modules\BaseModuleServiceProvider;

class OrderServiceProvider extends BaseModuleServiceProvider
{
    protected $moduleName = 'Order';

    public function boot()
    {
        parent::boot();

        // Register views
        $this->loadViewsFrom(__DIR__.'/../Views', 'order');
    }

    public function register()
    {
        // Register module-specific services
    }
} 