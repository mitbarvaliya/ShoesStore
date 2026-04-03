<?php

namespace App\Providers;

use App\Services\MarketService;
use Illuminate\Support\ServiceProvider;

class MarketServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MarketService::class, function ($app) {
            return new MarketService();
        });
    }

    public function boot(): void
    {
        //
    }
}
