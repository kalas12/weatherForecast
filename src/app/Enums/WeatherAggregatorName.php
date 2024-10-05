<?php

declare(strict_types=1);

namespace App\Enums;

enum WeatherAggregatorName: string
{
	case WEATHER_API = 'WeatherAPI';
	case VISUALCROSSING_API = 'VisualcrossingApi';
}
