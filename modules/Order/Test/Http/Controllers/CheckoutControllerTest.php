<?php

namespace Http\Controllers;

use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Order\Models\Order;
use Modules\Order\Test\OrderTestCase;
use Modules\Payment\PayBuddy;
use Modules\Payment\Payment;
use PHPUnit\Framework\Attributes\Test;

class CheckoutControllerTest extends OrderTestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_successfully_creates_an_order()
    {
        $user = UserFactory::new()->create();
        $products = ProductFactory::new()->count(2)->create(
            new Sequence(
                ['name' => 'Very expensive product', 'price_in_cents' => 10000, 'stock' => 10],
                ['name' => 'Macbook pro', 'price_in_cents' => 50000, 'stock' => 10],
            )
        );

        $paymentToken = PayBuddy::validToken();

        $response = $this->actingAs($user)
            ->post(route('checkout', [
                'payment_token' => $paymentToken,
                'products' => [
                    ['id' => $products->first()->id, 'quantity' => 1],
                    ['id' => $products->last()->id, 'quantity' => 1],
                ],
            ]));

        $response->assertStatus(201);

        $order = Order::query()->latest('id')->first();

        // Order
        $this->assertTrue($order->user->is($user));
        $this->assertEquals(60000, $order->total_in_cents);
        $this->assertEquals('completed', $order->status);

        // Payment
        /** @var Payment $payment */
        $payment = $order->lastPayment;

        $this->assertEquals('paid', $payment->status);
        $this->assertEquals('PayBuddy', $payment->payment_gateway);
        $this->assertEquals(36, strlen($payment->payment_id));
        $this->assertEquals(60000, $payment->total_in_cents);
        $this->assertTrue($payment->user->is($user));

        $this->assertCount(2, $order->lines);

        foreach ($products as $product) {
            $orderLine = $order->lines->where('product_id', $product->id)->first();

            $this->assertEquals($product->price_in_cents, $orderLine->product_price_in_cents);
            $this->assertEquals(1, $orderLine->quantity);
        }

        $products = $products->fresh();

        $this->assertEquals(9, $products->first()->stock);
        $this->assertEquals(9, $products->last()->stock);
    }

    #[Test]
    public function it_fails_with_an_invalid_token()
    {
        $user = UserFactory::new()->create();
        $product = ProductFactory::new()->create();

        $paymentToken = PayBuddy::invalidToken();

        $response = $this->actingAs($user)
            ->postJson(route('checkout', [
                'payment_token' => $paymentToken,
                'products' => [
                    ['id' => $product->id, 'quantity' => 1],
                ],
            ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors('payment_token');
    }
}
