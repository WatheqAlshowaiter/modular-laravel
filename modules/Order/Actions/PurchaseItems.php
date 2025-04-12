<?php

namespace Modules\Order\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\DTOs\PendingPayment;
use Modules\Order\Events\OrderFulfilled;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Product\Dtos\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;
use Modules\User\UserDto;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
        protected Dispatcher $events
    ) {}

    public function handle(CartItemCollection $items, PendingPayment $pendingPayment, UserDto $userDto): OrderDto
    {
        /** @var OrderDto $order */
        $orderDto = $this->databaseManager->transaction(function () use ($items, $userDto, $pendingPayment) {
            $order = Order::startForUser($userDto->id);
            $order->addLinesFromCartItems($items);
            $order->fulfill();

            $this->createPaymentForOrder->handle(
                $order->id,
                $userDto->id,
                $items->totalInCents(),
                $pendingPayment->provider,
                $pendingPayment->paymentToken,
            );

            return OrderDto::fromEloquentModel($order);
        });

        $this->events->dispatch(
            new OrderFulfilled(
                $orderDto,
                $userDto,
            )
        );

        return $orderDto;
    }
}
