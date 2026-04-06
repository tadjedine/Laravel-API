<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
          return [
            'id' => $this->id_image,
            'product_id' => $this->id_product,
            'position' => $this->position,
            'cover' => (bool) $this->cover,
            'url'       => $this->getUrl('large_default'),     // main image
            'urls'      => $this->urls,
            'legend'    => $this->whenLoaded('lang')?->legend ?? null,
        ];
    }
}
