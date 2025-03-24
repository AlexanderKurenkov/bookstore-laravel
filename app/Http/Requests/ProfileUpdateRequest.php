<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:20'],

            'last_name' => ['nullable', 'string', 'max:30'],

            // Email validation
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:30',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            // Phone validation (nullable as it's optional in your form)
            'phone' => ['nullable', 'string', 'max:20'],

            // Date of birth validation (nullable, assuming no strict format)
            'date_of_birth' => ['nullable', 'date'],

            // Gender validation (nullable, only 'male' or 'female')
            'gender' => ['nullable', 'in:male,female'],
        ];
    }
}
