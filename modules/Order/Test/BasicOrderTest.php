<?php

namespace Modules\Order\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Order\src\Models\Order;
use Tests\TestCase;

class BasicOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic()
    {
        $order = Order::factory()->create();

        self::assertNotEmpty($order->total_in_cents);
    }
}
