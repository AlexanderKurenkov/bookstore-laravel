<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutProcessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Change if you have authorization logic
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required_if:delivery_method,standard', 'nullable', 'string', 'max:500'],
            'city' => ['required_if:delivery_method,standard', 'nullable', 'string', 'max:255'],
            'postal_code' => ['required_if:delivery_method,standard', 'nullable', 'string', 'max:20'],
            'delivery_method' => ['required', 'in:standard,pickup'],
            'payment_method' => ['required', 'in:card,cash'],
            'card_number' => ['required_if:payment_method,card', 'nullable', 'digits:16'],
            'card_expiry' => ['required_if:payment_method,card', 'nullable', 'date_format:m/y'],
            'card_cvv' => ['required_if:payment_method,card', 'nullable', 'digits:3'],
        ];
    }
}
