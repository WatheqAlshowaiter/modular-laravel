<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Modules\Order\Actions\PurchaseItems;
use Modules\Order\DTOs\PendingPayment;
use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Http\Requests\CheckoutRequests;
use Modules\Payment\PayBuddySdk;
use Modules\Product\Dtos\CartItemCollection;
use Modules\User\UserDto;

class CheckoutController
{
    public function __construct(
        protected PurchaseItems $purchaseItems
    ) {}

    public function __invoke(CheckoutRequests $request)
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));
        $pendingPayment = new PendingPayment(PayBuddySdk::make(), $request->input('payment_token'));
        $userDto = UserDto::fromEloquentModel($request->user());

        try {
            $orderDto = $this->purchaseItems->handle(
                items: $cartItems,
                pendingPayment: $pendingPayment,
                userDto: $userDto,
            );

        } catch (PaymentFailedException) {

            throw ValidationException::withMessages([
                'payment_token' => 'We could not complete your payment.',
            ]);
        }

        return response()->json([
            'order_url' => $orderDto->url,
        ], 201);
    }
}
