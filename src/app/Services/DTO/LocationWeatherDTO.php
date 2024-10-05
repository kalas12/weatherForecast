<?php

declare(strict_types=1);

namespace App\Services\DTO;

readonly class LocationWeatherDTO
{
	public function __construct(
		public string $name,
		public float $latitude,
		public float $longitude
	) {}
}
