<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use App\Domain\Inspections\Models\InspectionItem;
use App\Domain\Inspections\Observers\InspectionItemObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Domain\Events\Repositories\EventRepositoryInterface;
use App\Domain\Events\Repositories\EventRepository;
use App\Domain\Status\Repositories\StatusRepositoryInterface;
use App\Domain\Status\Repositories\StatusRepository;
use App\Domain\DocumentManagement\Repositories\DocumentRepository;
use App\Domain\DocumentManagement\Repositories\Interfaces\DocumentRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);
        $this->app->bind(DocumentRepositoryInterface::class, DocumentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('frontend.app', function ($view) {
            $isHome = Request::is('/');
            $view->with('isHome', $isHome);
        });
        View::composer('*', function ($view) {
            $guard = Auth::getDefaultDriver();  // 'tenant' veya 'client' değerini alır
            $view->with('guard', $guard);
        });
        // Register your component aliases
        Blade::componentNamespace('App\\View\\Components\\Tenant', 'tenant');
        Blade::componentNamespace('App\\View\\Components\\Client', 'client');
        Blade::componentNamespace('App\\View\\Components\\Employee', 'employee');

        // Observers
        InspectionItem::observe(InspectionItemObserver::class);

        Relation::morphMap([
            'inspection' => \App\Domain\Inspections\Models\Inspection::class,
            'invoice' => \App\Domain\Invoices\Models\Invoice::class,
        ]);
    }
}
