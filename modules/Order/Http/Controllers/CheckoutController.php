<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Modules\Order\Http\Requests\CheckoutRequests;
use Modules\Order\Models\Order;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;
use RuntimeException;

class CheckoutController
{
    public function __construct(
        public ProductStockManager $productStockManager,
    ) {}

    public function __invoke(CheckoutRequests $request)
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));

        $orderTotalInCents = $cartItems->totalInCents();

        $payBuddy = PayBuddy::make();

        try {
            $charge = $payBuddy->charge($request->input('payment_token'), $orderTotalInCents, 'Modular Laravel');
        } catch (RuntimeException) {
            throw ValidationException::withMessages([
                'payment_token' => 'We could not complete your payment.',
            ]);
        }

        $order = Order::create([
            'payment_id' => $charge['id'],
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'total_in_cents' => $orderTotalInCents,
            'user_id' => $request->user()->id,
        ]);

        foreach ($cartItems->items() as $cartItem) {
            $this->productStockManager->decrement(
                $cartItem->product->id,
                $cartItem->quantity,
            );

            $order->lines()->create([
                'product_id' => $cartItem->product->id,
                'product_price_in_cents' => $cartItem->product->priceInCents,
                'quantity' => $cartItem->quantity,
            ]);
        }

        return response()->json([], 201);
    }
}
