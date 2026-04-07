<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PsCategoryLang
 * 
 * @property int $id_category
 * @property int $id_shop
 * @property int $id_lang
 * @property string $name
 * @property string|null $description
 * @property string|null $additional_description
 * @property string $link_rewrite
 * @property string|null $meta_title
 * @property string|null $meta_description
 *
 * @package App\Models
 */
class PsCategoryLang extends Model
{
	protected $table = 'ps_category_lang';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_category' => 'int',
		'id_shop' => 'int',
		'id_lang' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'additional_description',
		'link_rewrite',
		'meta_title',
		'meta_description'
	];
}
