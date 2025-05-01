<?php

namespace Modules\Shipment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\src\Models\Order;

class Shipment extends Model
{

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
