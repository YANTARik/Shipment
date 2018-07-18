<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model {
	protected $fillable = [
		'code', 'name', 'qty',
	];

	public $timestamps = false;

	public static function form() {
		return [
			'code' => '',
			'name' => '',
			'qty' => '',
		];
	}
}
