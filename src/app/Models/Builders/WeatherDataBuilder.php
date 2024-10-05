<?php

declare(strict_types=1);

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WeatherDataBuilder
 *
 * Кастомный Eloquent Builder для модели WeatherData.
 *
 * @package App\Models\Builders
 */
final class WeatherDataBuilder extends Builder
{
	/**
	 * Фильтровать по имени локации.
	 *
	 * @param string|null $locationName
	 * @return self
	 */
	public function byLocationName(?string $locationName): self
	{
		if ($locationName !== null) {
			// Присоединяем таблицу locations и фильтруем по имени локации
			$this->whereHas('location', function (Builder $query) use ($locationName) {
				$query->where('name', 'like', "%{$locationName}%");
			});
		}

		return $this;
	}

	/**
	 * Фильтровать по диапазону дат (created_at).
	 *
	 * @param string|null $startDate
	 * @param string|null $endDate
	 * @return self
	 */
	public function byDateRange(?string $startDate, ?string $endDate): self
	{
		if ($startDate !== null && $endDate !== null) {
			$this->whereBetween('created_at', [$startDate, $endDate]);
		} elseif ($startDate !== null) {
			$this->where('created_at', '>=', $startDate);
		} elseif ($endDate !== null) {
			$this->where('created_at', '<=', $endDate);
		}

		return $this;
	}
}
