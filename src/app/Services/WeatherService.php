<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Location;
use App\Repositories\WeatherDataRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

readonly class WeatherService
{
	public function __construct(private WeatherDataRepository $repository)
	{
	}

	/**
	 * Получить локацию по названию.
	 *
	 * @param string $locationName
	 * @param string $startDate
	 * @param string $endDate
	 * @return Collection
	 */
	public function getAverageWeatherByLocationAndPeriod(
		string $locationName,
		string $startDate,
		string $endDate
	): Collection
	{
		return $this->repository->getWeatherDataByFilters($locationName, $startDate, $endDate);
	}

	/**
	 * Получить или закэшировать средние данные о погоде для конкретной локации и периода.
	 *
	 * @param Location $location
	 * @param string $startDate
	 * @param string $endDate
	 * @return array|null
	 */
	public function getOrCacheAverageWeather(Location $location, string $startDate, string $endDate): ?array
	{
		// Генерируем ключ для кэша
		$cacheKey = "weather_average_{$location->id}_{$startDate}_{$endDate}";

		// Кэширование результата
		return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($location, $startDate, $endDate) {
			// Запрашиваем данные о погоде и считаем средние значения
			$weatherData = $this->getAverageWeatherByLocationAndPeriod($location->name, $startDate, $endDate);

			if ($weatherData->isEmpty()) {
				return null;
			}

			// Рассчитываем средние значения
			$averageTemperature = $weatherData->avg('temperature');
			$averageHumidity = $weatherData->avg('humidity');

			return [
				'average_temperature' => round($averageTemperature, 2),
				'average_humidity' => round($averageHumidity, 2),
			];
		});
	}
}
