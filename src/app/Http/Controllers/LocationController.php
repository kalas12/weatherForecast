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

	public function store(StoreLocationRequest $request): JsonResponse
	{
		$location = $this->locationService->create($request->getLocationDTO());

		return response()->json(['message' => "Локация {$location->name} успешно создана."],201);
	}

	public function getAverageWeather(GetAverageWeatherRequest $request, string $locationName): JsonResponse
	{
		$location = $this->locationService->getByName($locationName);

		if (is_null($location)) {
			return response()->json(['message' => "Локация {$locationName} не найдена."],404);
		}

		$startDate = $request->getStartDate();
		$endDate = $request->getEndDate();

		$averageWeather = $this->weatherService->getAverageWeather($location, $startDate, $endDate);

		if (is_null($averageWeather)) {
			return response()->json(['message' => "Нет данных о погоде для локации {$location->name} за указанный период."],204);
		}

		return response()->json($averageWeather);
	}
}
