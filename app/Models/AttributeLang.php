<?php

namespace App\Models;

/**
 * Class AttributeLang
 *
 * @property int $id_attribute
 * @property int $id_lang
 * @property string $name
 *
 * @package App\Models
 */
class AttributeLang extends PrestashopModel
{
	protected $table = 'ps_attribute_lang';
	public $timestamps = false;
	public $incrementing = false;

	protected $casts = [
		'id_attribute' => 'int',
		'id_lang' => 'int',
	];

	protected $fillable = [
		'id_attribute',
		'id_lang',
		'name',
	];
}
