<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms the checkout summary array into a clean, consistent API response.
 * Wraps the array returned by CheckoutService::getSummary().
 */
class CheckoutResource extends JsonResource
{
    /**
     * Disable the default "data" wrapper since we pass a raw array, not a Model.
     */
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->resource; // the raw summary array

        return [
            'cart_id'           => $data['cart_id'],
            'customer_id'       => $data['customer_id'],

            'delivery_address'  => $data['delivery_address'],
            'invoice_address'   => $data['invoice_address'],

            'carrier'           => $data['carrier'],

            'items'             => $data['items'],
            'total_quantity'    => $data['total_quantity'],

            'subtotal'          => $data['subtotal'],
            'discount_summary'  => $data['discount_summary'],
            'total_discounts'   => $data['total_discounts'],
            'shipping_cost'     => $data['shipping_cost'],
            'total'             => $data['total'],

            'is_ready'          => $data['is_ready'],
            'validation_errors' => $data['validation_errors'],
        ];
    }
}
