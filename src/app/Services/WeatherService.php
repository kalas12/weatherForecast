<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Location;
use App\Models\WeatherData;
use App\Repositories\WeatherDataRepository;
use App\Services\DTO\WeatherDataDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

readonly class WeatherService
{
	public function __construct(private WeatherDataRepository $repository)
	{
	}

	/**
	 * Получить прогноз погоды по локации в промежутке дат.
	 *
	 * @param string $locationName
	 * @param string $startDate
	 * @param string $endDate
	 * @return Collection
	 */
	public function getWeatherData(
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
	public function getAverageWeather(Location $location, string $startDate, string $endDate): ?array
	{
		// Генерируем ключ для кэша
		$cacheKey = "weather_average_{$location->id}_{$startDate}_{$endDate}";

		// Кэширование результата
		return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($location, $startDate, $endDate) {
			// Запрашиваем данные о погоде и считаем средние значения
			$weatherData = $this->getWeatherData($location->name, $startDate, $endDate);

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

	/**
	 * Создать новый прогноз погоды.
	 *
	 * @param WeatherDataDTO $weatherDataDTO
	 * @param int $locationId
	 * @param string $source
	 * @return WeatherData
	 */
	public function create(WeatherDataDTO $weatherDataDTO, int $locationId, string $source): WeatherData
	{
		return $this->repository->create($weatherDataDTO, $locationId, $source);
	}
}
