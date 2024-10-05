<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Services\DTO\LocationDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => 'required|string|max:255',
			'latitude' => 'required|numeric|between:-90,90',
			'longitude' => 'required|numeric|between:-180,180',
		];
	}

	/**
	 * Создать экземпляр LocationDTO из данных запроса.
	 *
	 * @return LocationDTO
	 */
	public function getLocationDTO(): LocationDTO
	{
		return new LocationDTO(
			name: $this->input('name'),
			latitude: (float) $this->input('latitude'),
			longitude: (float) $this->input('longitude')
		);
	}
}
