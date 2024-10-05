<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\FetchWeatherDataJob;
use App\Models\Location;
use App\Repositories\LocationRepository;
use App\Services\DTO\LocationDTO;
use Exception;
use Illuminate\Database\Eloquent\Collection;

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
		return $this->repository->create($locationDTO);
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
