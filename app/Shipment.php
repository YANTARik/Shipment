<?php

namespace App;

use App\ShipmentItem;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model {
	protected $fillable = [
		'name',
	];

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function items() {
		return $this->hasMany(ShipmentItem::class);
	}

	public static function form() {
		return [
			'name' => '',
			'items' => [
				ShipmentItem::form(),
			],
		];
	}
}
