<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class FeatureValue
 *
 * @property int $id_feature_value
 * @property int $id_feature
 * @property int $custom
 *
 * @package App\Models
 */
class FeatureValue extends PrestashopModel
{
	protected $table = 'ps_feature_value';
	protected $primaryKey = 'id_feature_value';
	public $timestamps = false;

	protected $casts = [
		'id_feature' => 'int',
		'custom' => 'int',
	];

	protected $fillable = [
		'id_feature',
		'custom',
	];

	public function feature(): BelongsTo
	{
		return $this->belongsTo(Feature::class, 'id_feature', 'id_feature');
	}

	public function lang(): HasOne
	{
		return $this->hasOne(FeatureValueLang::class, 'id_feature_value', 'id_feature_value')
					->where('id_lang', 1);
	}

	public function getValueAttribute(): ?string
	{
		return $this->lang?->value;
	}
}
