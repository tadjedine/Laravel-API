<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PsStockAvailable
 * 
 * @property int $id_stock_available
 * @property int $id_product
 * @property int $id_product_attribute
 * @property int $id_shop
 * @property int $id_shop_group
 * @property int $quantity
 * @property int $physical_quantity
 * @property int $reserved_quantity
 * @property int $depends_on_stock
 * @property int $out_of_stock
 * @property string $location
 *
 * @package App\Models
 */
class StockAvailable extends Model
{
	protected $table = 'ps_stock_available';
	protected $primaryKey = 'id_stock_available';
	public $timestamps = false;

	protected $casts = [
		'id_product' => 'int',
		'id_product_attribute' => 'int',
		'id_shop' => 'int',
		'id_shop_group' => 'int',
		'quantity' => 'int',
		'physical_quantity' => 'int',
		'reserved_quantity' => 'int',
		'depends_on_stock' => 'int',
		'out_of_stock' => 'int'
	];

	protected $fillable = [
		'id_product',
		'id_product_attribute',
		'id_shop',
		'id_shop_group',
		'quantity',
		'physical_quantity',
		'reserved_quantity',
		'depends_on_stock',
		'out_of_stock',
		'location'
	];
}
