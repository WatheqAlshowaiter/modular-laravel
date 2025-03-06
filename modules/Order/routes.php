<?php

use Modules\Order\Http\Controllers\CheckoutController;

Route::middleware('auth')->group(function () {
    Route::post('checkout', CheckoutController::class)->name('checkout');
});
