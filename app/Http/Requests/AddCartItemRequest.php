<?php

namespace App\Http\Requests;

use App\Http\Middleware\GuestSessionMiddleware;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AddCartItemRequest extends FormRequest
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
        return [
            // 'id_customer' => ['required', 'integer', 'min:1'],
            'id_product' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('ps_product', 'id_product'),
            ],
            'quantity' => ['required', 'integer', 'min:1'],

            // variant/combination
            'id_product_attribute' => [
                'nullable',
                'integer',
                'min:0',
                Rule::when(
                    $this->input('id_product_attribute') > 0,
                    Rule::exists('ps_product_attribute', 'id_product_attribute')
                        ->where(fn ($q) => $q->where('id_product', $this->input('id_product')))
                ),
            ],

            // cart context used by service
            'id_customization' => ['nullable', 'integer', 'min:0'],
            'id_address_delivery' => ['nullable', 'integer', 'min:0'],
            'id_shop' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function customerId(): int
    {
        // Authenticated user — use their customer ID
        if ($this->user()) {
            return (int) $this->user()->id_customer;
        }

        // Existing guest session (set by middleware)
        $guestCustomerId = $this->attributes->get('guest_customer_id');
        if ($guestCustomerId) {
            return (int) $guestCustomerId;
        }

        // No session yet — create one on demand (lazy creation)
        $guest = GuestSessionMiddleware::createGuestSession();

        // Update the request attributes so context() can pick up guest_id
        $this->attributes->set('guest', $guest);
        $this->attributes->set('guest_id', (int) $guest->id_guest);
        $this->attributes->set('guest_customer_id', (int) $guest->id_customer);

        return (int) $guest->id_customer;
    }

    public function productId(): int
    {
        return (int) $this->validated('id_product');
    }

    public function quantity(): int
    {
        return (int) $this->validated('quantity');
    }

    public function context(): array
    {
        return array_filter([
            'id_product_attribute' => $this->validated('id_product_attribute'),
            'id_customization' => $this->validated('id_customization'),
            'id_address_delivery' => $this->validated('id_address_delivery'),
            'id_shop' => $this->validated('id_shop'),
            'id_guest'=> $this->attributes->get('guest_id')
        ], static fn ($value) => $value !== null);
    }

}
