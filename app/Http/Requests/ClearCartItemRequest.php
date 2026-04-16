<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ClearCartItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_customer' => ['required', 'integer', 'min:1'],
            'id_product' => ['required', 'integer', 'min:1'],
            'id_product_attribute' => ['nullable', 'integer', 'min:0'],
            'id_customization' => ['nullable', 'integer', 'min:0'],
            'id_address_delivery' => ['nullable', 'integer', 'min:0'],
        ];

    }

    public function context():array
    {
        return array_filter([
            'id_product_attribute' => $validated['id_product_attribute'] ?? null,
            'id_customization' => $validated['id_customization'] ?? null,
            'id_address_delivery' => $validated['id_address_delivery'] ?? null,
         ], static fn ($value) => $value !== null);
    }

}
