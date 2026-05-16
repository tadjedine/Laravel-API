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
            'quantity'          => $this->quantity,
            'active'            => (bool) $this->active,
            'on_sale'           => (bool) $this->on_sale,
            'condition'         => $this->condition,
            'category_id'       => $this->id_category_default,
            'date_add'          => $this->date_add,
            'cover_image'       => $this->whenLoaded('coverImage', function () {
                return $this->coverImage ? [
                    'id'  => $this->coverImage->id_image,
                    'url' => $this->coverImage->getUrl('large_default'),
                    'urls' => $this->coverImage->urls,
                ] : null;
            }),
            'images'            => ProductImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
