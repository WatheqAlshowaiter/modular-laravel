<?php

namespace Modules\Order\src\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'order');

        $this->app->register(RouteServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'order');
        Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components', 'order');

        // you can register your components here as a single component
        //Blade::component('order-alert', Alert::class);

        // or you can register multiple components by registering namespace
        Blade::componentNamespace('Modules\\Order\\ViewComponents', 'order');
    }
}
