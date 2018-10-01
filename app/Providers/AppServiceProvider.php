<?php

namespace App\Providers;

use App\Routing\ResourceRegistrar;
use Illuminate\Routing\ResourceRegistrar as ResourceRegistrarLaravel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
        Schema::defaultStringLength(191); //
        // URL::forceScheme('https');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ResourceRegistrarLaravel::class, ResourceRegistrar::class);
    }
}
