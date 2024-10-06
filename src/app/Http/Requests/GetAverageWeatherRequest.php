<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAverageWeatherRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'start' => 'required|date|before_or_equal:end',
			'end' => 'required|date|after_or_equal:start'
		];
	}

	/**
	 * Вернуть с какой даты смотрим погоду
	 *
	 * @return string
	 */
	public function getStartDate(): string
	{
		return $this->input('start');
	}

	/**
	 * Вернуть до какой даты смотрим погоду
	 *
	 * @return string
	 */
	public function getEndDate(): string
	{
		return $this->input('end');
	}
}
