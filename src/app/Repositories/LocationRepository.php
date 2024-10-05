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
		$location = $this->getByName($locationDTO->name);

		if (is_null($location)) {
			$location = new Location();
			$location->name = $locationDTO->name;
			$location->latitude = $locationDTO->latitude;
			$location->longitude = $locationDTO->longitude;
			$location->save();
		}

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
	 * Получить все локации.
	 *
	 * @return Collection<int, Location>
	 */
	public function getAllLocations(): Collection
	{
		return Location::all();
	}
}
