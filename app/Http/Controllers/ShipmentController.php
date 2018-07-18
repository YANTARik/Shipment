<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\ShipmentItem;
use App\User;
use Illuminate\Http\Request;

class ShipmentController extends Controller {
	public function __construct() {
		$this->middleware('auth:api')
			->except(['index', 'show']);
	}

	public function index() {
		$shipments = Shipment::orderBy('created_at', 'desc')
			->get(['id', 'name']);

		return response()
			->json([
				'shipments' => $shipments,
			]);
	}

	public function create() {
		$form = Shipment::form();
		return response()
			->json([
				'form' => $form,
			]);
	}

	public function store(Request $request) {
		$this->validate($request, [
			'name' => 'required|max:255',
			'items' => 'required|array|min:1',
			'items.*.code' => 'required|max:255',
			'items.*.name' => 'required|max:255',
			'items.*.qty' => 'required|max:255',
		]);

		$items = [];

		foreach ($request->items as $item) {
			$items[] = new ShipmentItem($item);
		}

		$shipment = new Shipment($request->only('name'));
		$request->user()->shipments()
			->save($shipment);

		$shipment->items()
			->saveMany($items);

		return response()
			->json([
				'saved' => true,
				'id' => $shipment->id,
				'message' => 'You have successfully created new shipment!',
			]);
	}

	public function show($id) {
		//dd($request->all());
		$shipment = Shipment::with(['user', 'items'])
			->findOrFail($id);

		return response()
			->json([
				'shipment' => $shipment,
			]);
	}

	public function edit($id, Request $request) {
		$form = $request->user()->shipments()
			->with(['items' => function ($query) {
				$query->get(['id', 'name', 'code', 'qty']);
			}])
			->findOrFail($id, [
				'id', 'name',
			]);

		return response()
			->json([
				'form' => $form,
			]);
	}

	public function update($id, Request $request) {
		//dd($request->all());
		$this->validate($request, [
			'name' => 'required|max:255',
			'items' => 'required|array|min:1',
			'items.*.id' => 'integer|exists:shipment_items',
			'items.*.code' => 'required|max:255',
			'items.*.name' => 'required|max:255',
			'items.*.qty' => 'required|max:255',
		]);

		$shipment = $request->user()->shipments()
			->findOrFail($id);

		$items = [];
		$itemsUpdated = [];

		foreach ($request->items as $item) {
			if (isset($item['id'])) {
				ShipmentItem::where('shipment_id', $shipment->id)
					->where('id', $item['id'])
					->update($item);
				$itemsUpdated[] = $item['id'];
			} else {
				$items[] = new ShipmentItem($item);
			}
		}

		$shipment->name = $request->name;

		$shipment->save();

		ShipmentItem::whereNotIn('id', $itemsUpdated)
			->where('shipment_id', $shipment->id)
			->delete();

		if (count($items)) {
			$shipment->items()->saveMany($items);
		}

		return response()
			->json([
				'saved' => true,
				'id' => $shipment->id,
				'message' => 'You have successfully updated shipment!',
			]);
	}

	public function destroy($id, Request $request) {
		$shipment = $request->user()->shipments()
			->findOrFail($id);

		ShipmentItem::where('shipment_id', $shipment->id)
			->delete();

		$shipment->delete();

		return response()
			->json([
				'deleted' => true,
			]);
	}

}
