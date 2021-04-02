<?php
namespace App\Actions;

use App\Models\Order;
use App\Models\User;
use App\Models\Entry;
use App\Models\Multiplier;

class CreateEntryAction
{
	public function execute($shopId, $orderId, $storeCustomerId, $points = 0)
	{
		try {
			$multiplier = Multiplier::latest()->first();

			$entry = Entry::updateOrCreate(
				['order_id' => $orderId],
				[
					'user_id' => $shopId,
					'customer_id' => $storeCustomerId,
					'multiplier_id' => $multiplier->id,
					'points' => $points,
				]
			);

			logger("Entry created/updated for $storeCustomerId" . json_encode($entry));

			return $entry;

		} catch (\Exception $e) {
			//throw $th;
			\Log::error($e->getMessage());
			\Log::error($e->getTraceAsString());

			\DB::rollback();
		}
	}
}