<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutProcessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Can be changed for a custom authorization logic.
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Поле "Имя" обязательно для заполнения.',
            'first_name.string' => 'Поле "Имя" должно быть строкой.',
            'first_name.max' => 'Поле "Имя" не должно превышать 255 символов.',

            'last_name.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'last_name.string' => 'Поле "Фамилия" должно быть строкой.',
            'last_name.max' => 'Поле "Фамилия" не должно превышать 255 символов.',

            'email.required' => 'Поле "E-mail" обязательно для заполнения.',
            'email.email' => 'Поле "E-mail" должно содержать корректный адрес.',

            'phone.required' => 'Поле "Телефон" обязательно для заполнения.',
            'phone.string' => 'Поле "Телефон" должно быть строкой.',
            'phone.max' => 'Поле "Телефон" не должно превышать 20 символов.',

            'address.required_if' => 'Поле "Адрес" обязательно для доставки.',
            'address.string' => 'Поле "Адрес" должно быть строкой.',
            'address.max' => 'Поле "Адрес" не должно превышать 500 символов.',

            'city.required_if' => 'Поле "Город" обязательно для доставки.',
            'city.string' => 'Поле "Город" должно быть строкой.',
            'city.max' => 'Поле "Город" не должно превышать 255 символов.',

            'postal_code.required_if' => 'Поле "Почтовый индекс" обязательно для доставки.',
            'postal_code.string' => 'Поле "Почтовый индекс" должно быть строкой.',
            'postal_code.max' => 'Поле "Почтовый индекс" не должно превышать 20 символов.',

            'delivery_method.required' => 'Выберите способ доставки.',
            'delivery_method.in' => 'Выбран неверный способ доставки.',

            'payment_method.required' => 'Выберите способ оплаты.',
            'payment_method.in' => 'Выбран неверный способ оплаты.',

            'card_number.required_if' => 'Поле "Номер карты" обязательно при оплате картой.',
            'card_number.digits' => 'Поле "Номер карты" должно содержать ровно 16 цифр.',

            'card_expiry.required_if' => 'Поле "Срок действия карты" обязательно при оплате картой.',
            'card_expiry.date_format' => 'Поле "Срок действия карты" должно быть в формате MM/YY.',

            'card_cvv.required_if' => 'Поле "CVV-код" обязательно при оплате картой.',
            'card_cvv.digits' => 'Поле "CVV-код" должен содержать ровно 3 цифры.',

            'terms.required' => 'Вы должны принять условия соглашения.',
            'terms.accepted' => 'Вы должны согласиться с условиями.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'card_number' => preg_replace('/\D/', '', $this->card_number), // Remove non-digits
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:20'],   // Matches schema
            'last_name' => ['required', 'string', 'max:30'],    // Matches schema
            'email' => ['required', 'email', 'max:30'],         // Matches schema
            'phone' => ['required', 'string', 'max:20'],        // Matches schema

            'address' => ['required_if:delivery_method,standard', 'string', 'max:255'],   // Matches delivery_details schema
            'city' => ['required_if:delivery_method,standard', 'string', 'max:100'],      // Matches schema
            'region' => ['required_if:delivery_method,standard', 'string', 'max:100'],    // Matches `state VARCHAR(100)`
            'postal_code' => ['required_if:delivery_method,standard', 'string', 'max:20'], // Matches schema

            'delivery_method' => ['required', 'in:standard,pickup'],   // Matches allowed values
            'payment_method' => ['required', 'in:card,cash'],          // Matches allowed values

            'card_number' => ['required_if:payment_method,card', 'digits:16'],  // Card should be exactly 16 digits
            'card_expiry' => ['required_if:payment_method,card', 'date_format:m/y'], // Enforces MM/YY format
            'card_cvv' => ['required_if:payment_method,card', 'digits:3'],      // CVV must be exactly 3 digits
        ];
    }
}
