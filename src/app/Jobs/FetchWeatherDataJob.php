<?php

namespace App\Jobs;

use App\Services\LocationService;
use App\Services\WeatherApi\WeatherAggregator;
use App\Services\WeatherService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;

class FetchWeatherDataJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Execute the job.
	 *
	 * @param WeatherAggregator $aggregator
	 * @param WeatherService $weatherService
	 * @param LocationService $locationService
	 * @return void
	 */
	public function handle(
		WeatherAggregator $aggregator,
		WeatherService $weatherService,
		LocationService $locationService
	): void {
		$locationService->getAllLocationsInChunks(config('app.chunk_size'), function ($locations) use ($aggregator, $weatherService) {
			foreach ($locations as $location) {
				$weatherDataList = $aggregator->aggregateWeatherData($location);
				try {
					DB::transaction(function () use ($weatherDataList, $location, $weatherService) {
						foreach ($weatherDataList as $weatherDataDTO) {
							$weatherService->create($weatherDataDTO, $location->id, $weatherDataDTO->source);
						}
					});

					Log::channel('info')->info("Записи о погоде для локации с ID {$location->id} успешно созданы.");
				} catch (\Exception $e) {
					Log::channel('error')->error("Ошибка при создании записей о погоде для локации с ID {$location->id}: " . $e->getMessage());
				}
			}
		});
	}
}
