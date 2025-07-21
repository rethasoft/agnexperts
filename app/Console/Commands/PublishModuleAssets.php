<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishModuleAssets extends Command
{
    protected $signature = 'module:publish-assets {module?}';
    protected $description = 'Publish assets for all or specific module';

    public function handle()
    {
        $moduleName = $this->argument('module');

        if ($moduleName) {
            $this->publishModuleAssets($moduleName);
        } else {
            $this->publishAllModulesAssets();
        }
    }

    protected function publishModuleAssets($moduleName)
    {
        $source = app_path("Modules/{$moduleName}/Assets");
        $destination = public_path("modules/" . strtolower($moduleName));

        if (File::exists($source)) {
            File::copyDirectory($source, $destination);
            $this->info("Published assets for module: {$moduleName}");
        } else {
            $this->error("Assets directory not found for module: {$moduleName}");
        }
    }

    protected function publishAllModulesAssets()
    {
        $modules = File::directories(app_path('Modules'));

        foreach ($modules as $moduleDir) {
            $moduleName = basename($moduleDir);
            $this->publishModuleAssets($moduleName);
        }
    }
} 