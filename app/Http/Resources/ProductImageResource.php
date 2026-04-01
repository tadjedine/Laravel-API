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
            'image_path' => $this->image_path ?? null,
            'position' => $this->position,
            'cover' => (bool) $this->cover,
        ];
    }
}
