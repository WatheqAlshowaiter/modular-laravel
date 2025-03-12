<?php

namespace Modules\Payment\Actions;

use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Payment\PayBuddy;
use Modules\Payment\Payment;
use RuntimeException;

class CreatePaymentForOrder
{
    public function handle(
        int $orderId,
        int $userId,
        int $totalInCents,
        PayBuddy $payBuddy,
        string $paymentToken
    ): Payment {
        try {
            $charge = $payBuddy->charge($paymentToken, $totalInCents, 'Modular Laravel');
        } catch (RuntimeException) {
            throw PaymentFailedException::dueToInvalidToken();
        }

        return Payment::create([
            'order_id' => $orderId,
            'total_in_cents' => $totalInCents,
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'payment_id' => $charge['id'],
            'user_id' => $userId,
        ]);
    }
}
