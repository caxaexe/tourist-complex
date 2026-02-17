<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'],
            'amount'     => ['required', 'numeric', 'min:0.01'],
            'method'     => ['required', 'in:cash,card,transfer'],
            'paid_at'    => ['nullable', 'date'],
            'note'       => ['nullable', 'string', 'max:5000'],

            //  booking_id запретили принимать из формы
            'booking_id' => ['prohibited'],
        ];
    }
}
