<?php

namespace Modules\Payment\Actions;

use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Payment\PayBuddySdk;
use Modules\Payment\Payment;
use RuntimeException;

class CreatePaymentForOrder
{
    /**
     * @throws PaymentFailedException
     */
    public function handle(
        int $orderId,
        int $userId,
        int $totalInCents,
        PayBuddySdk $payBuddy,
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
