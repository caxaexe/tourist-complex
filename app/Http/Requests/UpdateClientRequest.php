<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'passport_series' => ['nullable', 'string', 'max:16'],
            'passport_number' => ['nullable', 'string', 'max:32'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
        ];
    }
}
