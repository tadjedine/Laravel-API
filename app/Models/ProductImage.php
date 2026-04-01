<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
class ProductImage extends Model
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
		return $this->belongsTo(Product::class, 'id_product');
	}
}
