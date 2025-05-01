<?php

namespace Modules\Order\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequests extends FormRequest
{
    public function rules(): array
    {
        return [
            'payment_token' => ['required', 'string'],
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'numeric'],
            'products.*.quantity' => ['required', 'numeric'],
        ];
    }
}
