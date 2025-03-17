<?php

namespace Modules\Order\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Product\Dtos\CartItemCollection;

class OrderFulfilled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $orderId,
        public int $totalInCents,
        public string $localizedTotal,
        public CartItemCollection $cartItems,
        public int $userId,
        public string $userEmail,

    ) {
        //
    }
}
