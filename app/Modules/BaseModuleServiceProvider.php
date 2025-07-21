<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    protected $moduleName;

    public function boot()
    {
        if ($this->moduleName) {
            // Load views
            $this->loadViewsFrom(__DIR__ . "/{$this->moduleName}/Views", strtolower($this->moduleName));

            // Load migrations
            $this->loadMigrationsFrom(__DIR__ . "/{$this->moduleName}/Database/Migrations");

            // Load config
            $configPath = __DIR__ . "/{$this->moduleName}/Config/config.php";
            if (file_exists($configPath)) {
                $this->mergeConfigFrom($configPath, strtolower($this->moduleName));
            }
        }
    }
} 