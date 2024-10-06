<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GetAverageWeatherRequest;
use App\Http\Requests\StoreLocationRequest;
use App\Services\LocationService;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
	public function __construct(
		private readonly LocationService $locationService,
		private readonly WeatherService $weatherService
	) {}

	/**
	 * Добавить новую локацию.
	 *
	 * @param StoreLocationRequest $request
	 * @return JsonResponse
	 */
	public function store(StoreLocationRequest $request): JsonResponse
	{
		$location = $this->locationService->create($request->getLocationDTO());

		return response()->json(['message' => "Локация {$location->name} успешно создана."],201);
	}

	/**
	 * Получить средние значения температуры и влажности по указанной локации за период.
	 *
	 * @param GetAverageWeatherRequest $request
	 * @param string $locationName
	 * @return JsonResponse
	 */
	public function getAverageWeather(GetAverageWeatherRequest $request, string $locationName): JsonResponse
	{
		$location = $this->locationService->getByName($locationName);

		if (is_null($location)) {
			return response()->json(['message' => "Локация {$locationName} не найдена."],400);
		}

		$startDate = $request->getStartDate();
		$endDate = $request->getEndDate();

		$averageWeather = $this->weatherService->getOrCacheAverageWeather($location, $startDate, $endDate);

		if (is_null($averageWeather)) {
			return response()->json(['message' => "Нет данных о погоде для локации {$location->name} за указанный период."],400);
		}

		return response()->json($averageWeather);
	}
}
