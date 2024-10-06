<?php

namespace App\Jobs;

use App\Repositories\LocationRepository;
use App\Services\WeatherApi\WeatherAggregator;
use App\Repositories\WeatherDataRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchWeatherDataJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Execute the job.
	 *
	 * @param WeatherAggregator $aggregator
	 * @param WeatherDataRepository $weatherDataRepository
	 * @param LocationRepository $locationRepository
	 * @return void
	 */
	public function handle(
		WeatherAggregator $aggregator,
		WeatherDataRepository $weatherDataRepository,
		LocationRepository $locationRepository
	): void {
		$locationRepository->getAllLocationsInChunks(config('app.chunk_size'), function ($locations) use ($aggregator, $weatherDataRepository) {
			foreach ($locations as $location) {
				$weatherDataList = $aggregator->aggregateWeatherData($location);
				$weatherDataRepository->createInTransaction($weatherDataList, $location->id);
			}
		});
	}
}
