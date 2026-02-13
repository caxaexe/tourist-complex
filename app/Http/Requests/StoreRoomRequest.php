<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'number' => ['required', 'string', 'max:50', 'unique:rooms,number'],
        'room_type_id' => ['nullable', 'exists:room_types,id'],
        'title' => ['nullable', 'string', 'max:255'],
        'capacity' => ['nullable', 'integer', 'min:1', 'max:50'],
        'price_per_night' => ['required', 'numeric', 'min:0'],
        'description' => ['nullable', 'string'],
        'is_active' => ['nullable', 'boolean'],
    ];
}
}
