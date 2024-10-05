<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * Пространство имён контроллеров.
	 *
	 * @var string
	 */
	protected $namespace = 'App\\Http\\Controllers';

	/**
	 * Определение маршрутов для приложения.
	 *
	 * @return void
	 */
	public function map()
	{
		$this->mapApiRoutes();

		$this->mapWebRoutes();
	}

	/**
	 * Определение маршрутов API.
	 *
	 * @return void
	 */
	protected function mapApiRoutes(): void
	{
		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/api.php'));
	}

	/**
	 * Определение маршрутов веб.
	 *
	 * @return void
	 */
	protected function mapWebRoutes(): void
	{
		Route::middleware('web')
			->namespace($this->namespace)
			->group(base_path('routes/web.php'));
	}
}
