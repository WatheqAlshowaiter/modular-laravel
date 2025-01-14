<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Order\Models\Order;

class orderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'total_in_cents' => $this->faker->randomNumber(),
            'payment_gateway' => $this->faker->word(),
            'payment_id' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
