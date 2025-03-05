<?php

namespace Modules\Shipment\Providers;

use Illuminate\Support\ServiceProvider;

class ShipmentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'shipment');

        $this->app->register(RouteServiceProvider::class);

    }
}
