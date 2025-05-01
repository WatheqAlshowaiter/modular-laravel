<?php

return [
    App\Providers\AppServiceProvider::class,
    \Modules\Order\src\Providers\OrderServiceProvider::class,
    \Modules\Product\Providers\ProductServiceProvider::class,
    \Modules\Shipment\Providers\ShipmentServiceProvider::class,
    \Modules\Payment\Infrastructure\Providers\PaymentServiceProvider::class,
];
