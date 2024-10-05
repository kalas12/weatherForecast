<?php

namespace App\Services\WeatherApi;

use App\Enums\WeatherAggregatorName;
use App\Models\Location;
use App\Services\DTO\WeatherDataDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class WeatherApiService implements WeatherSourceInterface
{
	private Client $client;
	private string $apiKey;

	public function __construct()
	{
		$this->client = new Client([
			'base_uri' => config('services.weatherApi.base_uri'),
			'timeout'  => 10.0,
		]);
		$this->apiKey = config('services.weatherApi.key');
	}

	public function fetchWeatherData(Location $location): WeatherDataDTO
	{
		try {
			$response = $this->client->get('current.json', [
				'query' => [
					'key' => $this->apiKey,
					'q'   => "{$location->latitude},{$location->longitude}",
					'aqi' => 'no',
				],
			]);

			$data = json_decode($response->getBody()->getContents(), true);

			return new WeatherDataDTO(
				source: WeatherAggregatorName::WEATHER_API->value,
				temperature: (float) $data['current']['temp_c'],
				humidity: (float) $data['current']['humidity'],
				weatherCondition: $data['current']['condition']['text']
			);

		} catch (RequestException $e) {
			Log::channel('error')->error('WeatherAPI Error: ' . $e->getMessage());
			throw new \Exception('Не удалось получить данные о погоде от WeatherAPI');
		} catch (\Exception $e) {
			Log::channel('error')->error('Unexpected Error: ' . $e->getMessage());
			throw new \Exception('Произошла непредвиденная ошибка при получении данных о погоде');
		}
	}
}
