<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Location;
use App\Repositories\WeatherDataRepository;
use Illuminate\Database\Eloquent\Collection;

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
}
