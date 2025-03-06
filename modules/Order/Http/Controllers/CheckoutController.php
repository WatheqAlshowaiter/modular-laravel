<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Modules\Order\Http\Requests\CheckoutRequests;
use Modules\Order\Models\Order;
use Modules\Payment\PayBuddy;
use Modules\Product\Models\Product;
use RuntimeException;

class CheckoutController
{
    public function __invoke(CheckoutRequests $request)
    {
        $products = $request->collect('products')->map(function ($product) {
            return [
                'product' => Product::find($product['id']),
                'quantity' => $product['quantity'],
            ];
        });

        $orderTotalInCents = $products->sum(fn ($product) => $product['quantity'] * $product['product']->price_in_cents
        );

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

        foreach ($products as $product) {
            $product['product']->decrement('stock', $product['quantity']);

            $order->lines()->create([
                'product_id' => $product['product']->id,
                'product_price_in_cents' => $product['product']->price_in_cents,
                'quantity' => $product['quantity'],
            ]);
        }

        return response()->json([], 201);
    }
}
