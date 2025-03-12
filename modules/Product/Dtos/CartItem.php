<?php

namespace Modules\Product\Dtos;

readonly class CartItem
{
    public function __construct(
        public ProductDto $product,
        public int $quantity
    ) {}
}
