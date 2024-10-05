<?php

namespace App\Providers;

use App\Models\WeatherData;
use App\Repositories\WeatherDataRepository;
use App\Services\WeatherApi\VisualCrossingService;
use Illuminate\Support\ServiceProvider;
use App\Services\WeatherApi\WeatherSourceInterface;
use App\Services\WeatherApi\WeatherApiService;
use App\Services\WeatherApi\WeatherAggregator;

class WeatherServiceProvider extends ServiceProvider
{
	/**
	 * Регистрация сервисов в контейнере зависимостей.
	 *
	 * @return void
	 */
	public function register(): void
	{
		// Регистрация WeatherApiService как Singleton
		$this->app->singleton(WeatherApiService::class, function ($app) {
			return new WeatherApiService();
		});

		// Регистрация VisualCrossingService как Singleton
		$this->app->singleton(VisualCrossingService::class, function ($app) {
			return new VisualCrossingService();
		});

		// Регистрация WeatherAggregatorName как Singleton с одним источником
		$this->app->singleton(WeatherAggregator::class, function ($app) {
			$weatherApiService = $app->make(WeatherApiService::class);
			$visualCrossingService = $app->make(VisualCrossingService::class);

			// Добавляем несколько источников в WeatherAggregator
			return new WeatherAggregator([$weatherApiService, $visualCrossingService]);
		});

		// Привязка интерфейса к конкретной реализации
		$this->app->bind(WeatherSourceInterface::class, function ($app) {
			return $app->make(WeatherApiService::class);
		});

		// Регистрация WeatherDataRepository как Singleton
		$this->app->singleton(WeatherDataRepository::class, function ($app) {
			return new WeatherDataRepository(new WeatherData());
		});
	}

	/**
	 * Загрузка сервисов.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		//
	}
}
