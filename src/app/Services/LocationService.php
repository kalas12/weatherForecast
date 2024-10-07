<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Location;
use App\Repositories\LocationRepository;
use App\Services\DTO\LocationDTO;

readonly class LocationService
{
	public function __construct(private LocationRepository $repository)
	{
	}

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
			$location = $this->repository->create($locationDTO);
		}

		return $location;
	}

	/**
	 * Получить локацию по названию.
	 *
	 * @param string $name
	 * @return Location|null
	 */
	public function getByName(string $name): ?Location
	{
		return $this->repository->getByName($name);
	}
}
