<?php

declare(strict_types=1);

namespace App\Services\WeatherApi;

use App\Models\Location;
use App\Services\DTO\WeatherDataDTO;
use Exception;

interface WeatherSourceInterface
{
	/**
	 * Получить данные о погоде для заданной локации.
	 *
	 * @param Location $location
	 * @return WeatherDataDTO
	 *
	 * @throws Exception Если данные не удалось получить.
	 */
	public function fetchWeatherData(Location $location): WeatherDataDTO;
}
