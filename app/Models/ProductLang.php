<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PsProductLang
 * 
 * @property int $id_product
 * @property int $id_shop
 * @property int $id_lang
 * @property string|null $description
 * @property string|null $description_short
 * @property string $link_rewrite
 * @property string|null $meta_description
 * @property string|null $meta_title
 * @property string $name
 * @property string|null $available_now
 * @property string|null $available_later
 * @property string|null $delivery_in_stock
 * @property string|null $delivery_out_stock
 *
 * @package App\Models
 */
class ProductLang extends Model
{
	protected $table = 'ps_product_lang';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_product' => 'int',
		'id_shop' => 'int',
		'id_lang' => 'int'
	];

	protected $fillable = [
		'description',
		'description_short',
		'link_rewrite',
		'meta_description',
		'meta_title',
		'name',
		'available_now',
		'available_later',
		'delivery_in_stock',
		'delivery_out_stock'
	];
}
