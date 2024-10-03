<?php

namespace Sarojsardar\EsewaEpay;

use Illuminate\Support\ServiceProvider;

class EsewaEpayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publishing configuration file
        $this->publishes([
            __DIR__.'/config/esewa.php' => config_path('esewa.php'),
        ], 'config'); // Tag for configuration
    }

    public function register()
    {
        // Register the service
        $this->app->singleton(EpayService::class, function ($app) {
            return new EpayService();
        });
    }
}
