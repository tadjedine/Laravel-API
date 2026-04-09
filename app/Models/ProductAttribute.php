<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\CartProduct;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PsProductAttribute
 * 
 * @property int $id_product_attribute
 * @property int $id_product
 * @property string|null $reference
 * @property string|null $supplier_reference
 * @property string|null $ean13
 * @property string|null $isbn
 * @property string|null $upc
 * @property string|null $mpn
 * @property float $wholesale_price
 * @property float $price
 * @property float $ecotax
 * @property float $weight
 * @property float $unit_price_impact
 * @property int|null $default_on
 * @property int $minimal_quantity
 * @property int|null $low_stock_threshold
 * @property bool $low_stock_alert
 * @property Carbon|null $available_date
 *
 * @package App\Models
 */
class ProductAttribute extends Model
{
	protected $table = 'ps_product_attribute';
	protected $primaryKey = 'id_product_attribute';
	public $timestamps = false;

	protected $casts = [
		'id_product' => 'int',
		'wholesale_price' => 'float',
		'price' => 'float',
		'ecotax' => 'float',
		'weight' => 'float',
		'unit_price_impact' => 'float',
		'default_on' => 'int',
		'minimal_quantity' => 'int',
		'low_stock_threshold' => 'int',
		'low_stock_alert' => 'bool',
		'available_date' => 'datetime'
	];

	protected $fillable = [
		'id_product',
		'reference',
		'supplier_reference',
		'ean13',
		'isbn',
		'upc',
		'mpn',
		'wholesale_price',
		'price',
		'ecotax',
		'weight',
		'unit_price_impact',
		'default_on',
		'minimal_quantity',
		'low_stock_threshold',
		'low_stock_alert',
		'available_date'
	];


	public function product(): BelongsTo
	{
    return $this->belongsTo(Product::class, 'id_product', 'id_product');
	}

public function cartProducts(): HasMany
	{
    return $this->hasMany(CartProduct::class, 'id_product_attribute', 'id_product_attribute');
	}
}
