<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\LocationRepository;
use App\Services\LocationService;
use App\Models\Location;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Регистрация сервисов.
	 *
	 * @return void
	 */
	public function register(): void
	{
//		$this->app->singleton(LocationRepository::class, function ($app) {
//			return new LocationRepository(new Location());
//		});
//
//		$this->app->singleton(LocationService::class, function ($app) {
//			return new LocationService($app->make(LocationRepository::class));
//		});
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		//
	}
}
