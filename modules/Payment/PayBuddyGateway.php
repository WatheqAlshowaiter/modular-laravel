<?php

namespace Modules\Payment;

use Modules\Payment\Exceptions\PaymentFailedException;
use RuntimeException;

class PayBuddyGateway implements PaymentGateway
{
    public function __construct(
        public PayBuddySdk $payBuddySdk
    ) {
    }

    /**
     * @throws PaymentFailedException
     */
    public function charge(PaymentDetails $details): SuccessfulPayment
    {
        try {
            $charge = $this->payBuddySdk->charge(
                $details->token,
                $details->amountInCents,
                $details->statementDescription
            );
        } catch (RuntimeException) {
            throw PaymentFailedException::dueToInvalidToken();
        }

        return new SuccessfulPayment(
            $charge['id'],
            $charge['amount_in_cents'],
            $this->id()
        );
    }

    public function id(): PaymentProvider
    {
        return PaymentProvider::PayBuddy;
    }
}
