<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;

class BillingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind('App\Acme\Billing\BillingInterface', 'App\Acme\Billing\StripeBilling');
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}