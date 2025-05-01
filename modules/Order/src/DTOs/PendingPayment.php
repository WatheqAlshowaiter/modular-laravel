<?php

namespace Modules\Order\src\DTOs;

use Modules\Payment\PaymentGateway;

readonly class PendingPayment
{
    public function __construct(
        public PaymentGateway $provider,
        public string $paymentToken,
    ) {}
}
