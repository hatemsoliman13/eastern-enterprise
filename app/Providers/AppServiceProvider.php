<?php

namespace App\Providers;

use App\Services\GeoLocation\GeoLocationInterface;
use App\Services\GeoLocation\PositionStack;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GeoLocationInterface::class, PositionStack::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
