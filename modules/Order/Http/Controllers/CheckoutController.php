<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Modules\Order\Actions\PurchaseItems;
use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Http\Requests\CheckoutRequests;
use Modules\Payment\PayBuddy;
use Modules\Product\Dtos\CartItemCollection;

class CheckoutController
{
    public function __construct(
        protected PurchaseItems $purchaseItems
    ) {}

    public function __invoke(CheckoutRequests $request)
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));

        try {
            $order = $this->purchaseItems->handle(
                items: $cartItems,
                paymentProvider: PayBuddy::make(),
                paymentToken: $request->input('payment_token'),
                userId: $request->user()->id,
                userEmail: $request->user()->email
            );
        } catch (PaymentFailedException) {
            throw ValidationException::withMessages([
                'payment_token' => 'We could not complete your payment.',
            ]);
        }

        return response()->json([
            'order_url' => $order->url(),
        ], 201);
    }
}
