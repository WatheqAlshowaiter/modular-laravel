<?php

namespace Modules\Order\src\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Order\src\Events\OrderFulfilled;
use Modules\Order\src\Mail\OrderReceived;

class SendOrderConfirmationEmail
{
    public function handle(OrderFulfilled $event): void
    {
        Mail::to($event->userDto->email)->send(new OrderReceived($event->order->localizedTotal));
    }
}
