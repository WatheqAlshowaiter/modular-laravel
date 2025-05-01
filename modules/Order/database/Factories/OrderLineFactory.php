<?php

namespace Modules\Order\database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Order\src\Models\Order;
use Modules\Order\src\Models\OrderLine;
use Modules\Product\Models\Product;

class OrderLineFactory extends Factory
{
    protected $model = OrderLine::class;

    public function definition(): array
    {
        return [
            'product_price_in_cents' => $this->faker->randomNumber(),
            'quantity' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
