<?php

namespace App\Services\DTO;

readonly class WeatherDataDTO
{
	public function __construct(
		public string $source,
		public float $temperature,
		public float $humidity,
		public string $weatherCondition
	) {}
}
