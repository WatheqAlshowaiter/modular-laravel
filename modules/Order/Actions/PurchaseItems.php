<?php

namespace Modules\Order\Actions;

use Illuminate\Database\DatabaseManager;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws \Throwable
     */
    public function handle(
        CartItemCollection $items,
        PayBuddy $paymentProvider,
        string $paymentToken,
        int $userId,
    ): Order {

        return $this->databaseManager->transaction(function () use (
            $items,
            $paymentProvider,
            $paymentToken,
            $userId
        ) {
            $orderTotalInCents = $items->totalInCents();

            $order = Order::create([
                'status' => 'completed',
                'total_in_cents' => $orderTotalInCents,
                'user_id' => $userId,
            ]);

            foreach ($items->items() as $cartItem) {
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

            $this->createPaymentForOrder->handle(
                $order->id,
                $userId,
                $orderTotalInCents,
                $paymentProvider,
                $paymentToken
            );

            return $order;
        });
    }
}
