<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a new module';

    protected $directories = [
        'Config',
        'Controllers',
        'Controllers/Api',
        'Database/Migrations',
        'Database/Seeders',
        'Models',
        'Providers',
        'Routes',
        'Services',
        'Views',
    ];

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $modulePath = app_path("Modules/$name");

        if (File::exists($modulePath)) {
            $this->error("Module $name already exists!");
            return;
        }

        // Create module directories
        $this->createModuleDirectories($name);

        // Create basic files
        $this->createModuleFiles($name);

        $this->info("Module $name created successfully!");
    }

    protected function createModuleDirectories($name)
    {
        $modulePath = app_path("Modules/$name");

        foreach ($this->directories as $directory) {
            $path = "$modulePath/$directory";
            File::makeDirectory($path, 0755, true);
        }
    }

    protected function createModuleFiles($name)
    {
        $modulePath = app_path("Modules/$name");

        // Create service provider
        $this->createServiceProvider($name, $modulePath);

        // Create routes files
        $this->createRouteFiles($name, $modulePath);

        // Create config file
        $this->createConfigFile($name, $modulePath);
    }

    protected function createServiceProvider($name, $path)
    {
        $stub = File::get(__DIR__ . '/stubs/module-provider.stub');
        $content = str_replace(
            ['{{ModuleName}}', '{{moduleName}}'],
            [$name, strtolower($name)],
            $stub
        );

        File::put("$path/Providers/{$name}ServiceProvider.php", $content);
    }

    protected function createRouteFiles($name, $path)
    {
        // Create web.php
        File::put("$path/Routes/web.php", $this->getRouteStub('web'));
        
        // Create api.php
        File::put("$path/Routes/api.php", $this->getRouteStub('api'));
    }

    protected function createConfigFile($name, $path)
    {
        $content = "<?php\n\nreturn [\n    'name' => '$name',\n];";
        File::put("$path/Config/config.php", $content);
    }

    protected function getRouteStub($type)
    {
        return "<?php\n\nuse Illuminate\Support\Facades\Route;\n";
    }
} 