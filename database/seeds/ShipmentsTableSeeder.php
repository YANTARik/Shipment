<?php

use App\Shipment;
use App\ShipmentItem;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ShipmentsTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$faker = Factory::create();

		Shipment::truncate();
		ShipmentItem::truncate();

		foreach (range(1, 20) as $i) {
			$shipment = Shipment::create([
				'user_id' => 1,
				'name' => $faker->sentence,
			]);

			foreach (range(1, mt_rand(3, 12)) as $j) {
				ShipmentItem::create([
					'shipment_id' => $shipment->id,
					'name' => $faker->word,
					'code' => str_random(4),
					'qty' => mt_rand(1, 12),
				]);
			}
		}
	}
}
