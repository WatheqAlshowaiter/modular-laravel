<?php

use Modules\Order\src\Http\Controllers\CheckoutController;
use Modules\Order\src\Models\Order;

Route::middleware('auth')->group(function () {
    Route::post('checkout', CheckoutController::class)->name('checkout');

    Route::get('orders/{order}', function (Order $order) {
        return $order;
    })->name('orders.show');
});
