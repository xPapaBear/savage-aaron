<?php
namespace App\Actions;

use App\Models\Order;
use App\Models\User;
use App\Models\Entry;
use App\Models\Multiplier;

class CreateEntryAction
{
	public function execute($shopId, $orderId, $storeCustomerId)
	{
		try {
			# Multiplier
			$multiplier = Multiplier::latest()->first();

			# Order
			$order = Order::find($orderId);

			# Entry points
			$points = $order->total_line_items_price * $multiplier['value'];

			$order = Entry::updateOrCreate(
				['order_id' => $orderId],
				[
					'user_id' => $shopId,
					'customer_id' => $storeCustomerId,
					'multiplier_id' => $multiplier->id,
					'points' => $points,
					'st'
				]
			);

			return $order;

		} catch (\Exception $e) {
			//throw $th;
			\Log::error($e->getMessage());
			\Log::error($e->getTraceAsString());

			\DB::rollback();
		}
	}
}