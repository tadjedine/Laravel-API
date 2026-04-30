<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Reliese\Coders\Model\Relations\BelongsTo;

/**
 * Class PsImage
 *
 * @property int $id_image
 * @property int $id_product
 * @property int $position
 * @property int|null $cover
 *
 * @package App\Models
 */
class ProductImage extends PrestashopModel
{
	protected $table = 'ps_image';
	protected $primaryKey = 'id_image';
	public $timestamps = false;

	protected $casts = [
		'id_product' => 'int',
		'position' => 'int',
		'cover' => 'int'
	];

	protected $fillable = [
		'id_product',
		'position',
		'cover'
	];

	public function product()
	{
		return $this->belongsTo(Product::class, 'id_product', 'id_product');
	}

	// public function lang()
    // {
    //     return $this->hasOne(ImageLang::class, 'id_image', 'id_image');
    // }

	public function getImgPath(): string
    {
        $id = (string) $this->id_image;
        return implode('/', str_split($id));
    }

	public function getUrl(string $type = 'large_default'): string
    {
        $path = $this->getImgPath();
        $baseUrl = config('prestashop.base_url');

        return "{$baseUrl}/img/p/{$path}/{$this->id_image}-{$type}.jpg";
    }

	public function getUrlsAttribute(): array
    {
        return [
            'original' => $this->getUrl('large_default'),
            'home'     => $this->getUrl('home_default'),
            'medium'   => $this->getUrl('medium_default'),
            'small'    => $this->getUrl('small_default'),
            'cart'     => $this->getUrl('cart_default'),
        ];
    }

}
