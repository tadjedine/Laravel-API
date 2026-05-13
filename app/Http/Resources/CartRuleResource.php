<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartRuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $idLang = (int) ($request->query('id_lang', 1) ?: 1);

        // Resolve name from eager-loaded langs
        $lang = $this->langs?->firstWhere('id_lang', $idLang)
            ?? $this->langs?->first();
        $name = $lang?->name ?? $this->code;

        // Determine discount type and value
        [$discountType, $discountValue] = $this->resolveDiscountType();

        return [
            'id'                       => (int) $this->id_cart_rule,
            'code'                     => $this->code,
            'name'                     => $name,
            'discount_type'            => $discountType,
            'discount_value'           => $discountValue,
            'free_shipping'            => (bool) $this->free_shipping,
            'gift_product_id'          => (int) $this->gift_product > 0 ? (int) $this->gift_product : null,
            'gift_product_attribute_id' => (int) $this->gift_product_attribute > 0
                ? (int) $this->gift_product_attribute
                : null,
        ];
    }

    private function resolveDiscountType(): array
    {
        if ((float) $this->reduction_percent > 0) {
            return ['percent', round((float) $this->reduction_percent, 2)];
        }

        if ((float) $this->reduction_amount > 0) {
            return ['fixed', round((float) $this->reduction_amount, 2)];
        }

        if ($this->free_shipping) {
            return ['free_shipping', 0.0];
        }

        if ((int) $this->gift_product > 0) {
            return ['gift', 0.0];
        }

        return ['fixed', 0.0];
    }
}
