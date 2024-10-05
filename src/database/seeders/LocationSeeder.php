<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		DB::table('locations')->insert([
			[
				'name' => 'New York',
				'latitude' => 40.712776,
				'longitude' => -74.005974,
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Los Angeles',
				'latitude' => 34.052235,
				'longitude' => -118.243683,
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'London',
				'latitude' => 51.507351,
				'longitude' => -0.127758,
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Paris',
				'latitude' => 48.856613,
				'longitude' => 2.352222,
				'created_at' => now(),
				'updated_at' => now(),
			],
		]);
    }
}
