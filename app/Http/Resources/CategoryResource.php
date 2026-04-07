<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id_category,
            'name' => $this->name ?? 'Unnamed Category',
            'parent_id' => $this->id_parent,
            'active' => (bool) $this->active,
            'position' => $this->position,
            'level_depth' => $this->level_depth,
            'is_root' => (bool) $this->is_root_category,
            // 'products_count' => $this->when(isset($this->products), $this->products->count()),
            // 'children_count' => $this->when(isset($this->children), $this->children->count()),
        ];
    }
}
