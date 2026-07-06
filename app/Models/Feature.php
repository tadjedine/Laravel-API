<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Feature
 *
 * @property int $id_feature
 * @property int $position
 *
 * @package App\Models
 */
class Feature extends PrestashopModel
{
	protected $table = 'ps_feature';
	protected $primaryKey = 'id_feature';
	public $timestamps = false;

	protected $casts = [
		'position' => 'int',
	];

	protected $fillable = [
		'position',
	];

	public function lang(): HasOne
	{
		return $this->hasOne(FeatureLang::class, 'id_feature', 'id_feature')
					->where('id_lang', config('app.prestashop_lang', 1));
	}

	public function values(): HasMany
	{
		return $this->hasMany(FeatureValue::class, 'id_feature', 'id_feature');
	}

	public function getNameAttribute(): ?string
	{
		return $this->lang?->name;
	}
}
