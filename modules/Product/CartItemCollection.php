<?php

namespace Modules\Product;

use Illuminate\Support\Collection;
use Modules\Product\Models\Product;

class CartItemCollection
{
    /**
     * @param  Collection<CartItem>  $items
     */
    public function __construct(
        public Collection $items,
    ) {}

    public static function fromCheckoutData(array $data): CartItemCollection
    {
        $cartItems = collect($data)->map(function ($product) {
            return new CartItem(
                ProductDto::fromEloquentModel(Product::find($product['id'])),
                $product['quantity']
            );
        });

        return new self($cartItems);
    }

    public function totalInCents()
    {
        return $this->items->sum(function (CartItem $cartItem) {
            return $cartItem->quantity * $cartItem->product->priceInCents;
        });
    }

    /**
     * @return Collection<CartItem>
     */
    public function items(): Collection
    {
        return $this->items;
    }
}
