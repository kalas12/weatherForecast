<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Builders\WeatherDataBuilder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property int $id
 * @property int $location_id
 * @property string $source
 * @property float $temperature
 * @property float $humidity
 * @property string $weather_condition
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class WeatherData extends Model
{
	protected $table = 'weather_data';

	/**
	 * Кастинг атрибутов модели.
	 *
	 * @var array
	 */
	protected $casts = [
		'location_id' => 'integer',
		'temperature' => 'float',
		'humidity' => 'float',
	];

	/**
	 * Массив заполняемых атрибутов.
	 *
	 * @var array
	 */
	protected $fillable = [
		'location_id',
		'source',
		'temperature',
		'humidity',
		'weather_condition',
	];

	/**
	 * Связь с моделью Location.
	 *
	 * @return BelongsTo
	 */
	public function location()
	{
		return $this->belongsTo(Location::class);
	}

	/**
	 * Создаёт новый экземпляр кастомного билдера.
	 *
	 * @param Builder $query
	 * @return WeatherDataBuilder
	 */
	public function newEloquentBuilder($query): WeatherDataBuilder
	{
		return new WeatherDataBuilder($query);
	}
}
