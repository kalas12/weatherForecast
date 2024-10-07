<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Location;
use App\Services\DTO\LocationDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class LocationRepository
{
	/**
	 * Создать новую локацию.
	 *
	 * @param LocationDTO $locationDTO
	 * @return Location
	 */
	public function create(LocationDTO $locationDTO): Location
	{
		$location = new Location();
		$location->name = $locationDTO->name;
		$location->latitude = $locationDTO->latitude;
		$location->longitude = $locationDTO->longitude;
		$location->save();

		return $location;
	}

	/**
	 * Получить одну локацию по фильтрам.
	 *
	 * @param string $name
	 * @return Location|null
	 */
	public function getByName(string $name): ?Location
	{
		return Location::query()
			->byName($name)
			->first();
	}

	/**
	 * Получить все локации через чанки.
	 *
	 * @param int $chunkSize
	 * @param callable $callback
	 * @return void
	 */
	public function getAllLocationsInChunks(int $chunkSize, callable $callback): void
	{
		Location::chunk($chunkSize, $callback);
	}
}
