<?php


use Tests\TestCase;

class BasicOrderTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function testBasic()
    {

        $order = \Modules\Order\Models\Order::factory()->create();

        // this also works!
        //$order = \Modules\Order\Database\Factories\OrderFactory::new()->create();

        self::assertNotEmpty($order->id);
    }
}
