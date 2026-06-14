<?php

namespace App\Models;

/**
 * Class AttributeGroupLang
 *
 * @property int $id_attribute_group
 * @property int $id_lang
 * @property string $name
 * @property string $public_name
 *
 * @package App\Models
 */
class AttributeGroupLang extends PrestashopModel
{
	protected $table = 'ps_attribute_group_lang';
	public $timestamps = false;
	public $incrementing = false;

	protected $casts = [
		'id_attribute_group' => 'int',
		'id_lang' => 'int',
	];

	protected $fillable = [
		'id_attribute_group',
		'id_lang',
		'name',
		'public_name',
	];
}
