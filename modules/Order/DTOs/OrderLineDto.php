<?php

namespace Modules\Order\DTOs;

use Illuminate\Database\Eloquent\Collection;
use Modules\Order\Models\OrderLine;

class OrderLineDto
{
    public function __construct(
        public int $productId,
        public int $productPriceInCents,
        public string $quantity,
    ) {}

    public static function fromEloquentModel(OrderLine $orderLine): self
    {
        return new self($orderLine->product_id, $orderLine->product_price_in_cents, $orderLine->quantity);
    }

    /**
     * @return OrderLineDto[]
     */
    public static function fromEloquentCollection(Collection $orderLines): array
    {
        return $orderLines->map(fn (OrderLine $orderLine) => self::fromEloquentModel($orderLine))->all();
    }
}
