<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id_product,
            'name'              => $this->name ?? 'Unnamed Product',
            'description'       => $this->description,
            'description_short' => $this->description_short,
            'slug'              => $this->link_rewrite ?? 'product-' . $this->id_product,
            'price'             => (float) $this->price,
            'reference'         => $this->reference,
            'quantity'          => $this->whenLoaded('stockAvailable', fn() => $this->stockAvailable->sum('quantity'), $this->quantity),
            'active'            => (bool) $this->active,
            'on_sale'           => (bool) $this->on_sale,
            'condition'         => $this->condition,
            'category_id'       => $this->id_category_default,
            'date_add'          => $this->date_add,
            'product_type'      => $this->product_type,
            'cover_image'       => $this->whenLoaded('coverImage', function () {
                return $this->coverImage ? [
                    'id'  => $this->coverImage->id_image,
                    'url' => $this->coverImage->getUrl('large_default'),
                    'urls' => $this->coverImage->urls,
                ] : null;
            }),
            'images'            => ProductImageResource::collection($this->whenLoaded('images')),

            // Combination data — only populated on single-product detail requests
            'combinations'      => $this->whenLoaded('productAttribute', function () {
                if ($this->productAttribute->isEmpty()) {
                    return [];
                }

                return $this->productAttribute->map(function ($combo) {
                    $attrs = [];
                    foreach ($combo->attributes as $attr) {
                        $groupName = $attr->group?->name ?? 'Unknown';
                        $attrs[$groupName] = [
                            'id'    => $attr->id_attribute,
                            'value' => $attr->name,
                            'color' => $attr->color ?: null,
                        ];
                    }
                    return [
                        'id'           => $combo->id_product_attribute,
                        'price_impact' => (float) $combo->price,
                        'final_price'  => round((float) $this->price + (float) $combo->price, 2),
                        'quantity'     => $combo->stockAvailable?->quantity ?? 0,
                        'reference'    => $combo->reference,
                        'is_default'   => (bool) $combo->default_on,
                        'attributes'   => $attrs,
                        'image_ids'    => $combo->images->pluck('id_image')->toArray(),
                    ];
                });
            }),

            'attribute_groups'  => $this->whenLoaded('productAttribute', function () {
                if ($this->productAttribute->isEmpty()) {
                    return [];
                }

                // Collect unique attribute groups from all combinations
                $groups = collect();
                foreach ($this->productAttribute as $combo) {
                    foreach ($combo->attributes as $attr) {
                        $group = $attr->group;
                        if (!$group) continue;
                        $gid = $group->id_attribute_group;
                        if (!$groups->has($gid)) {
                            $groups[$gid] = [
                                'id'       => $gid,
                                'name'     => $group->name,
                                'type'     => $group->group_type,
                                'is_color' => (bool) $group->is_color_group,
                                'values'   => collect(),
                            ];
                        }
                        $groups[$gid]['values']->push([
                            'id'    => $attr->id_attribute,
                            'name'  => $attr->name,
                            'color' => $attr->color ?: null,
                        ]);
                    }
                }
                // Deduplicate values within each group
                return $groups->map(function ($g) {
                    $g['values'] = $g['values']->unique('id')->values();
                    return $g;
                })->values();
            }),
        ];
    }
}

