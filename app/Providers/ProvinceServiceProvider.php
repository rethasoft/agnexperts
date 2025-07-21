<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Province;

class ProvinceServiceProvider extends ServiceProvider
{
    public function register()
    {
        // codes here
    }

    public function boot()
    {
        if (!Cache::has('all_provinces')) {
            $provinces = Province::pluck('name', 'id')->all();
            Cache::forever('all_provinces', $provinces);
            Log::info('Provinces cached', ['count' => count($provinces)]);
        }

        $this->app->singleton('provinces', function () {
            return Cache::get('all_provinces');
        });
    }
}
