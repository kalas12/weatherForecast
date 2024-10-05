<?php

namespace App\Services\WeatherApi;

use App\Models\Location;
use App\Services\DTO\WeatherDataDTO;
use Illuminate\Support\Facades\Log;

readonly class WeatherAggregator
{
	/**
	 * @var WeatherSourceInterface[]
	 */
	private array $weatherSources;

	/**
	 * WeatherAggregatorName constructor.
	 *
	 * @param WeatherSourceInterface[] $weatherSources
	 */
	public function __construct(array $weatherSources)
	{
		$this->weatherSources = $weatherSources;
	}

	/**
	 * Собрать данные о погоде из всех источников для заданной локации.
	 *
	 * @param Location $location
	 * @return array
	 */
	public function aggregateWeatherData(Location $location): array
	{
		$aggregatedData = [];

		foreach ($this->weatherSources as $source) {
			try {
				$data = $source->fetchWeatherData($location);
				$aggregatedData[] = $data;
				Log::channel('info')->info("Данные о погоде получены от " . get_class($source));
			} catch (\Exception $e) {
				Log::channel('error')->error("Ошибка при получении данных от " . get_class($source) . ": " . $e->getMessage());
			}
		}

		return $aggregatedData;
	}
}
