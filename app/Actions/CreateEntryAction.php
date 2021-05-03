<?php
namespace App\Actions;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateEntryAction
{
	public function execute($shop, $orderId, $storeCustomerId, $multiplierId, $points = 0)
	{
		\Log::info( '===== CLASS :: CreateEntryAction :: START =====' );
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

			\Log::info( '$entry:: ' . json_encode( $entry ) );

			$customer = Customer::find($storeCustomerId);
			\Log::info( '$customer:: ' . json_encode( $customer ) );

			$totalEntries = $customer->total_points;
			\Log::info( '$totalEntries:: ' . json_encode( $totalEntries ) );

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
			
			\Log::info( 'Entry created/updated for' . json_encode( $storeCustomerId ) );

			return $entry;

		} catch (\Exception $e) {
			//throw $th;
			\Log::error($e->getMessage());
			\Log::error($e->getTraceAsString());
		}
		\Log::info( '===== CLASS :: CreateEntryAction :: END =====' );
	}
}