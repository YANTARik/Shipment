<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$faker = Factory::create();

		User::truncate();

		foreach (range(1, 10) as $i) {
			User::create([
				'name' => $faker->name,
				'email' => $faker->email,
				'password' => bcrypt('password'),
				'api_token' => str_random(60),
			]);
		}
	}
}
