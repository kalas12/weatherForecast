<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\WeatherData;
use App\Repositories\WeatherDataRepository;
use App\Services\DTO\WeatherDataDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeatherDataRepositoryTest extends TestCase
{
	use RefreshDatabase;

	public function testCreateWeatherData(): void
	{
		// Создаем объект WeatherDataDTO с обновленным конструктором
		$weatherDataDTO = new WeatherDataDTO(
			source: 'API',
			temperature: 25.5,
			humidity: 60.0,
			weatherCondition: 'Sunny'
		);
		$locationId = 1;

		// Создаем репозиторий
		$repository = new WeatherDataRepository();

		// Создаем новую запись через репозиторий
		$weatherData = $repository->create($weatherDataDTO, $locationId, $weatherDataDTO->source);

		// Проверяем, что запись успешно создана
		$this->assertDatabaseHas('weather_data', [
			'location_id' => $locationId,
			'source' => 'API',
			'temperature' => 25.5,
			'humidity' => 60.0,
			'weather_condition' => 'Sunny',
		]);

		// Проверяем, что возвращен объект WeatherData
		$this->assertInstanceOf(WeatherData::class, $weatherData);
	}

	public function testCreateInTransaction(): void
	{
		$weatherDataDTOs = [
			new WeatherDataDTO(
				source: 'API',
				temperature: 25.5,
				humidity: 60.0,
				weatherCondition: 'Sunny'
			),
			new WeatherDataDTO(
				source: 'API',
				temperature: 15.0,
				humidity: 80.0,
				weatherCondition: 'Rainy'
			),
		];
		$locationId = 1;

		// Создаем репозиторий
		$repository = new WeatherDataRepository();

		// Создаем записи через транзакцию
		$repository->createInTransaction($weatherDataDTOs, $locationId);

		// Проверяем, что записи успешно созданы
		$this->assertDatabaseCount('weather_data', 2);
		$this->assertDatabaseHas('weather_data', [
			'location_id' => $locationId,
			'temperature' => 25.5,
			'weather_condition' => 'Sunny',
		]);
		$this->assertDatabaseHas('weather_data', [
			'location_id' => $locationId,
			'temperature' => 15.0,
			'weather_condition' => 'Rainy',
		]);
	}
}
