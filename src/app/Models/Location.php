<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Builders\LocationBuilder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

/**
 * @property int $id
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Location extends Model
{
	protected $table = 'locations';

	/**
	 * Кастинг атрибутов модели.
	 *
	 */
	protected $casts = [
		'latitude' => 'float',
		'longitude' => 'float',
	];

	/**
	 * Массив заполняемых атрибутов.
	 *
	 */
	protected $fillable = [
		'name',
		'latitude',
		'longitude',
	];

	/**
	 * Создаёт новый экземпляр кастомного билдера.
	 *
	 * @param Builder $query
	 * @return LocationBuilder
	 */
	public function newEloquentBuilder($query): LocationBuilder
	{
		return new LocationBuilder($query);
	}
}
