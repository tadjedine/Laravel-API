<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomesliderSlideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $this->lang;

        return [
            'id'          => $this->id_homeslider_slides,
            'title'       => $lang?->title,
            'description' => $lang?->description,
            'legend'      => $lang?->legend,
            'url'         => $lang?->url,
            'image_url'   => $this->buildImageUrl($lang?->image),
            'position'    => $this->position,
        ];
    }

    /**
     * Build the full URL for a slider image.
     *
     * PrestaShop stores slider images at:
     * {base_url}/modules/ps_imageslider/images/{filename}
     */
    private function buildImageUrl(?string $filename): ?string
    {
        if (!$filename) {
            return null;
        }

        $baseUrl = rtrim(config('prestashop.base_url', ''), '/');

        return "{$baseUrl}/modules/ps_imageslider/images/{$filename}";
    }
}
