<?php

namespace App\Models;

/**
 * Class FeatureLang
 *
 * @property int $id_feature
 * @property int $id_lang
 * @property string $name
 *
 * @package App\Models
 */
class FeatureLang extends PrestashopModel
{
	protected $table = 'ps_feature_lang';
	public $timestamps = false;
	public $incrementing = false;

	protected $casts = [
		'id_feature' => 'int',
		'id_lang' => 'int',
	];

	protected $fillable = [
		'id_feature',
		'id_lang',
		'name',
	];
}
