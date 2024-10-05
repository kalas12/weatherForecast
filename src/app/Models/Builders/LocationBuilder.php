<?php

declare(strict_types=1);

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class LocationBuilder
 *
 * Кастомный Eloquent Builder для модели Location.
 *
 * @package App\Models\Builders
 */
final class LocationBuilder extends Builder
{
	/**
	 * Фильтровать по названию.
	 *
	 * @param string|null $name
	 * @return self
	 */
	public function byName(?string $name): self
	{
		if ($name !== null) {
			$this->where('name', 'like', "%{$name}%");
		}

		return $this;
	}

	/**
	 * Фильтровать по широте.
	 *
	 * @param float|null $latitude
	 * @return self
	 */
	public function byLatitude(?float $latitude): self
	{
		if ($latitude !== null) {
			$this->where('latitude', $latitude);
		}

		return $this;
	}

	/**
	 * Фильтровать по долготе.
	 *
	 * @param float|null $longitude
	 * @return self
	 */
	public function byLongitude(?float $longitude): self
	{
		if ($longitude !== null) {
			$this->where('longitude', $longitude);
		}

		return $this;
	}
}
