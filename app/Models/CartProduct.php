<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PsCartProduct
 * 
 * @property int $id_cart
 * @property int $id_product
 * @property int $id_address_delivery
 * @property int $id_shop
 * @property int $id_product_attribute
 * @property int $id_customization
 * @property int $quantity
 * @property Carbon $date_add
 *
 * @package App\Models
 */
class PsCartProduct extends Model
{
	protected $table = 'ps_cart_product';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_cart' => 'int',
		'id_product' => 'int',
		'id_address_delivery' => 'int',
		'id_shop' => 'int',
		'id_product_attribute' => 'int',
		'id_customization' => 'int',
		'quantity' => 'int',
		'date_add' => 'datetime'
	];

	protected $fillable = [
		'id_shop',
		'quantity',
		'date_add'
	];
}
