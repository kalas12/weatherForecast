<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GetAverageWeatherRequest;
use App\Http\Requests\StoreLocationRequest;
use App\Services\LocationService;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(title="Weather API", version="1.0")
 */
class LocationController extends Controller
{
	public function __construct(
		private readonly LocationService $locationService,
		private readonly WeatherService $weatherService
	) {}

	/**
	 * @OA\Post(
	 *     path="/locations",
	 *     tags={"Locations"},
	 *     summary="Добавить новую локацию",
	 *     description="Добавление новой локации с указанием имени, широты и долготы.",
	 *     operationId="addLocation",
	 *     @OA\RequestBody(
	 *         required=true,
	 *         @OA\JsonContent(
	 *             @OA\Property(
	 *                 property="name",
	 *                 type="string",
	 *                 description="Имя локации",
	 *                 example="Москва"
	 *             ),
	 *             @OA\Property(
	 *                 property="latitude",
	 *                 type="number",
	 *                 description="Широта локации",
	 *                 example=55.7558
	 *             ),
	 *             @OA\Property(
	 *                 property="longitude",
	 *                 type="number",
	 *                 description="Долгота локации",
	 *                 example=37.6173
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=201,
	 *         description="Локация успешно создана",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(
	 *                 property="message",
	 *                 type="string",
	 *                 example="Локация Москва успешно создана."
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Неверный запрос",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="error", type="string", example="Некорректные данные")
	 *         )
	 *     )
	 * )
	 */
	public function store(StoreLocationRequest $request): JsonResponse
	{
		$location = $this->locationService->create($request->getLocationDTO());

		return response()->json(['message' => "Локация {$location->name} успешно создана."],201);
	}

	/**
	 * @OA\Get(
	 *     path="/locations/{locationName}/weather",
	 *     tags={"Locations"},
	 *     summary="Получить средние погодные данные для локации",
	 *     description="Получение средних погодных данных для указанной локации по имени и диапазону дат.",
	 *     operationId="getAverageWeather",
	 *     @OA\Parameter(
	 *         name="locationName",
	 *         in="path",
	 *         required=true,
	 *         description="Имя локации.",
	 *         @OA\Schema(
	 *             type="string",
	 *             example="Москва"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="start",
	 *         in="query",
	 *         required=true,
	 *         description="Дата начала для данных о погоде.",
	 *         @OA\Schema(
	 *             type="string",
	 *             format="date",
	 *             example="2024-01-01"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="end",
	 *         in="query",
	 *         required=true,
	 *         description="Дата окончания для данных о погоде.",
	 *         @OA\Schema(
	 *             type="string",
	 *             format="date",
	 *             example="2024-01-31"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Успешный ответ",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="average_temperature", type="string", example="14.02"),
	 *             @OA\Property(property="average_humidity", type="string", example="75.08")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="Локация Москва не найдена",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="error", type="string", example="Локация не найдена")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=204,
	 *         description="Нет данных о погоде для данной локации и указанного диапазона дат",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="Нет данных о погоде для локации Москва за указанный период.")
	 *         )
	 *     ),
	 * 	   @OA\Response(
	 *         response=400,
	 *         description="Неверный запрос",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="error", type="string", example="Некорректные данные")
	 *         )
	 *     )
	 * )
	 */
	public function getAverageWeather(GetAverageWeatherRequest $request, string $locationName): JsonResponse
	{
		$location = $this->locationService->getByName($locationName);

		if (is_null($location)) {
			return response()->json(['message' => "Локация {$locationName} не найдена."],404);
		}

		$startDate = $request->getStartDate();
		$endDate = $request->getEndDate();

		$averageWeather = $this->weatherService->getOrCacheAverageWeather($location, $startDate, $endDate);

		if (is_null($averageWeather)) {
			return response()->json(['message' => "Нет данных о погоде для локации {$location->name} за указанный период."],204);
		}

		return response()->json($averageWeather);
	}
}
