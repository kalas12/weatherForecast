<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\WeatherData;
use App\Services\DTO\WeatherDataDTO;
use Illuminate\Database\Eloquent\Collection;

readonly class WeatherDataRepository
{
	/**
	 * Создать новую запись о погоде.
	 *
	 * @param WeatherDataDTO $weatherDataDTO
	 * @param int $locationId
	 * @param string $source
	 * @return WeatherData
	 */
	public function create(WeatherDataDTO $weatherDataDTO, int $locationId, string $source): WeatherData
	{
		$weatherData = new WeatherData();
		$weatherData->location_id = $locationId;
		$weatherData->source = $source;
		$weatherData->temperature = $weatherDataDTO->temperature;
		$weatherData->humidity = $weatherDataDTO->humidity;
		$weatherData->weather_condition = $weatherDataDTO->weatherCondition;
		$weatherData->save();

		return $weatherData;
	}

	/**
	 * Получить записи о погоде по фильтрам.
	 *
	 * @param string $name
	 * @param string $startDate
	 * @param string $endDate
	 * @return Collection<int, WeatherData>
	 */
	public function getWeatherDataByFilters(string $name, string $startDate, string $endDate): Collection
	{
		return WeatherData::query()
			->byLocationName($name)
			->byDateRange($startDate, $endDate)
			->get();
	}
}
