<?php
namespace App\Actions;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\Multiplier;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateEntryAction
{
	public function execute($shop, $orderId, $storeCustomerId, $multiplierId, $points = 0)
	{
		try {
			$entry = Entry::updateOrCreate(
				['order_id' => $orderId],
				[
					'user_id' => $shop->id,
					'customer_id' => $storeCustomerId,
					'multiplier_id' => $multiplierId,
					'points' => $points,
				]
			);

			$customer = Customer::find($storeCustomerId);

			$totalEntries = $customer->total_points;

			$user = User::where('id', $shop->id)->first();
			Auth::login($user);
			$shop = Auth::user();

			$metafields = $shop->api()->request(
				'POST',
				"/admin/api/2020-10/customers/{$customer->store_customer_id}/metafields.json",
				['metafield' => [
					"namespace" => "giveaway",
					"key" => "entries",
					"value" => $totalEntries,
					"value_type" => "integer"
				]]
			);

			logger("Entry created/updated for $storeCustomerId");

			return $entry;

		} catch (\Exception $e) {
			//throw $th;
			\Log::error($e->getMessage());
			\Log::error($e->getTraceAsString());
		}
	}
}