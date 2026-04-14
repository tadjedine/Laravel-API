<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         $items = $this->products->map(function ($line) {
            $basePrice = (float) ($line->product?->price ?? 0);
            $impact = (float) ($line->combination?->price ?? 0);
            $unitPrice = $basePrice + $impact;
            $qty = (int) $line->quantity;

            return [
                'product_id' => (int) $line->id_product,
                'product_attribute_id' => (int) $line->id_product_attribute,
                'quantity' => $qty,
                'unit_price' => round($unitPrice, 6),
                'line_subtotal' => round($unitPrice * $qty, 2),
                'name' => $line->product?->name,
                'reference' => $line->product?->reference,
                'image' => $line->product?->images?->first()?->id_image,
            ];
        })->values();

        return [
            'id' => (int) $this->id_cart,
            'customer_id' => (int) $this->id_customer,
            'currency_id' => (int) $this->id_currency,
            'items' => $items,
            'total_quantity' => (int) $items->sum('quantity'),
            'subtotal' => round((float) $items->sum('line_subtotal'), 2),
        ];
    }
}
