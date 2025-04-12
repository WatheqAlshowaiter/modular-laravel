<?php

namespace Modules\Order\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\DTOs\OrderDto;
use Modules\User\UserDto;

readonly class OrderFulfilled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public OrderDto $order,
        public UserDto $userDto
    ) {
        //
    }
}
