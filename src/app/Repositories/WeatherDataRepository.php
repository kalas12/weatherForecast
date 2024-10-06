<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\WeatherData;
use App\Services\DTO\WeatherDataDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

	/**
	 * Создать новые записи о погоде в транзакции с логированием ошибок.
	 *
	 * @param array $weatherDataDTOs
	 * @param int $locationId
	 * @return void
	 */
	public function createInTransaction(array $weatherDataDTOs, int $locationId): void
	{
		try {
			DB::transaction(function () use ($weatherDataDTOs, $locationId) {
				foreach ($weatherDataDTOs as $weatherDataDTO) {
					$this->create($weatherDataDTO, $locationId, $weatherDataDTO->source);
				}
			});

			Log::channel('info')->info("Записи о погоде для локации с ID {$locationId} успешно созданы.");
		} catch (\Exception $e) {
			Log::channel('error')->error("Ошибка при создании записей о погоде для локации с ID {$locationId}: " . $e->getMessage());
		}
	}
}
