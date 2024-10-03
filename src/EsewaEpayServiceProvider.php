<?php

namespace Sarojsardar\EsewaEpay;

use Illuminate\Support\ServiceProvider;

class EsewaEpayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load configuration
        $this->publishes([
            __DIR__.'/config/esewa.php' => config_path('esewa.php'),
        ]);

        // Load routes, views, etc., if needed
        // $this->loadViewsFrom(__DIR__.'/views', 'esewa');
    }

    public function register()
    {
        $this->app->singleton(EpayService::class, function ($app) {
            return new EpayService();
        });
    }
}
