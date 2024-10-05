<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Запуск миграции.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('locations', function (Blueprint $table) {
			$table->id();
			$table->string('name')->index();
			$table->decimal('latitude', 10, 7);
			$table->decimal('longitude', 10, 7);
			$table->timestamps();

			// Композитный индекс на 'latitude' и 'longitude'
			$table->index(['latitude', 'longitude']);
		});
	}

	/**
	 * Откат миграции.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('locations');
	}
};
