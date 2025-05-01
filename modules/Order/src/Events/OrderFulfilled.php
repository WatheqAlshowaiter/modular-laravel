<?php

namespace Modules\Order\src\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\src\DTOs\OrderDto;
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
