<?php

namespace Modules\Order\src\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Modules\Order\src\Actions\PurchaseItems;
use Modules\Order\src\DTOs\PendingPayment;
use Modules\Order\src\Http\Requests\CheckoutRequests;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\PaymentGateway;
use Modules\Product\Dtos\CartItemCollection;
use Modules\User\UserDto;

class CheckoutController
{
    public function __construct(
        protected PurchaseItems $purchaseItems,
        public PaymentGateway $paymentGateway
    ) {
    }

    public function __invoke(CheckoutRequests $request)
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));
        $pendingPayment = new PendingPayment($this->paymentGateway, $request->input('payment_token'));
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
