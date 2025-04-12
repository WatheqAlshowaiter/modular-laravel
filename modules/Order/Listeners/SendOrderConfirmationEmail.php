<?php

namespace Modules\Order\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Order\Events\OrderFulfilled;
use Modules\Order\Mail\OrderReceived;

class SendOrderConfirmationEmail
{
    public function handle(OrderFulfilled $event): void
    {
        Mail::to($event->userDto->email)->send(new OrderReceived($event->order->localizedTotal));
    }
}
