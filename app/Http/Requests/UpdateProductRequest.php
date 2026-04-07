<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product');
        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'description' => ['sometimes', 'string', 'min:10'],
            'price' => ['sometimes', 'numeric', 'min:0.01'],
            'quantity' => ['sometimes', 'integer', 'min:0'],
            'id_category_default' => ['sometimes', 'integer', 'exists:ps_category,id_category'],
            'sku' => ['sometimes', 'string', 'unique:ps_product,reference,' . $productId . ',id_product'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Product name must be at least 3 characters',
            'price.numeric' => 'Price must be a valid number',
            'id_category_default.exists' => 'Selected category does not exist',
            'sku.unique' => 'SKU already exists',
        ];
    }
}
