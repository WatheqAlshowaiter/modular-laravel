<?php

namespace Modules\Order\src\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\src\Events\OrderFulfilled;
use Modules\Order\src\Listeners\SendOrderConfirmationEmail;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        OrderFulfilled::class => [
            SendOrderConfirmationEmail::class,
        ],
    ];
}
