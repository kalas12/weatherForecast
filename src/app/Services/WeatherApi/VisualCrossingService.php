<?php

namespace App\Services\WeatherApi;

use App\Enums\WeatherAggregatorName;
use App\Models\Location;
use App\Services\DTO\WeatherDataDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class VisualCrossingService implements WeatherSourceInterface
{
	private Client $client;
	private string $apiKey;

	public function __construct()
	{
		$this->client = new Client([
			'base_uri' => config('services.visualcrossingApi.base_uri'),
			'timeout'  => 10.0,
		]);
		$this->apiKey = config('services.visualcrossingApi.key');
	}

	public function fetchWeatherData(Location $location): WeatherDataDTO
	{
		try {
			$response = $this->client->get("{$location->latitude},{$location->longitude}", [
				'query' => [
					'key' => $this->apiKey,
					'unitGroup' => 'metric',
					'include' => 'current',
				],
			]);

			$data = json_decode($response->getBody()->getContents(), true);

			$currentData = $data['currentConditions'];

			return new WeatherDataDTO(
				source: WeatherAggregatorName::VISUALCROSSING_API->value,
				temperature: (float) $currentData['temp'],
				humidity: (float) $currentData['humidity'],
				weatherCondition: $currentData['conditions']
			);

		} catch (RequestException $e) {
			Log::channel('error')->error('VisualCrossing API Error: ' . $e->getMessage());
			throw new \Exception('Не удалось получить данные о погоде от Visual Crossing');
		} catch (\Exception $e) {
			Log::channel('error')->error('Unexpected Error: ' . $e->getMessage());
			throw new \Exception('Произошла непредвиденная ошибка при получении данных о погоде');
		}
	}
}
