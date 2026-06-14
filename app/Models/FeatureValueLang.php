<?php

namespace App\Models;

/**
 * Class FeatureValueLang
 *
 * @property int $id_feature_value
 * @property int $id_lang
 * @property string $value
 *
 * @package App\Models
 */
class FeatureValueLang extends PrestashopModel
{
	protected $table = 'ps_feature_value_lang';
	public $timestamps = false;
	public $incrementing = false;

	protected $casts = [
		'id_feature_value' => 'int',
		'id_lang' => 'int',
	];

	protected $fillable = [
		'id_feature_value',
		'id_lang',
		'value',
	];
}
