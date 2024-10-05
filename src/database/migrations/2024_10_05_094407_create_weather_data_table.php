<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherDataTable extends Migration
{
	/**
	 * Запуск миграций.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('weather_data', function (Blueprint $table) {
			$table->id();
			$table->foreignId('location_id')->constrained()->onDelete('cascade');
			$table->string('source');
			$table->decimal('temperature', 5, 2);
			$table->decimal('humidity', 5, 2);
			$table->string('weather_condition');
			$table->timestamps();
		});
	}

	/**
	 * Откат миграций.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('weather_data');
	}
}
