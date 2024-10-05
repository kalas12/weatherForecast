<?php

namespace App\Jobs;

use App\Repositories\LocationRepository;
use App\Services\WeatherApi\WeatherAggregator;
use App\Repositories\WeatherDataRepository;
use App\Services\DTO\WeatherDataDTO;
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
		foreach ($locationRepository->getAllLocations() as $location) {
			$weatherDataList = $aggregator->aggregateWeatherData($location);

			foreach ($weatherDataList as $dataDTO) {
				/** @var WeatherDataDTO $dataDTO */
				$weatherDataRepository->create($dataDTO, $location->id, $dataDTO->source);
			}
		}
	}
}
