<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('id');

        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:255', 'unique:ps_category_lang,name,' . $categoryId . ',id_category'],
            'description' => ['nullable', 'string'],
            'id_parent' => ['nullable', 'integer', 'exists:ps_category,id_category'],
            'active' => ['sometimes', 'boolean'],
            'position' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Category name must be at least 3 characters',
            'name.unique' => 'This category name already exists',
            'id_parent.exists' => 'Selected parent category does not exist',
        ];
    }
}
