<?php

namespace Modules\Order\DTOs;

use Modules\Payment\PayBuddySdk;

readonly class PendingPayment
{
    public function __construct(
        public PayBuddySdk $provider,
        public string $paymentToken,
    ) {}
}
