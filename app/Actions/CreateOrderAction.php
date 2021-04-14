<?php
namespace App\Actions;

use App\Models\Order;
use App\Models\User;
use App\Models\Multiplier;
use App\Actions\CreateEntryAction;
use Illuminate\Support\Arr;

class CreateOrderAction
{
	protected $createEntry;

	public function __construct() {
		$this->createEntry = new CreateEntryAction();
	}

	public function execute(string $shopDomain, object $data, object $customer)
	{
		if ( isset($data) && empty($data) ) return false;

		try {
			\DB::beginTransaction();

			$shop = User::domain($shopDomain)->first();

			// $multiplier = Multiplier::latest()
			// 	->whereDate('created_at', '<=', $data->created_at)
			// 	->first();

			$multiplier = Multiplier::whereDate('created_at', '<=', $data->created_at)
			->latest()
			->first(); 

			// $multiplier = Multiplier::whereDate('created_at', '<=', $data->created_at)
			// ->orderBy('created_at', 'DESC')
			// ->first();

			// Log::info($multiplier);
							

			$totalGiftCardAmount = 0;

			$giftCards = Arr::where((array) $data->line_items, function ($item, $key) {
				return isset($item) && isset($item['gift_card']) && $item['gift_card'] == true;
			});

			if (! empty($giftCards)) {
				foreach ($giftCards as $giftCard) {
					$totalGiftCardAmount += $giftCard->quantity * $giftCard->price;
				}
			}

			$totalPrice = $data->total_line_items_price - $totalGiftCardAmount;

			if ( isset($data) && isset($data->current_total_discounts) ) {
				$totalPrice = $totalPrice - $data->current_total_discounts;
			}

			$points = $totalPrice * $multiplier->value;

			$order = Order::updateOrCreate(
				['store_order_id' => $data->id],
				[
					'store_order_id' => $data->id,
					'user_id' => $shop['id'],
					'customer_id' => $customer->id,
					'multiplier_id' => $multiplier->id,
					'total_line_items_price' => $totalPrice,
					'raw' => json_encode($data)
				]
			);

			logger("Order created/updated" . $data->id);

			if ( $multiplier->status ) {
				$entry = $this->createEntry->execute($shop, $order->id, $customer->id, $multiplier->id, $points);
			}

			\DB::commit();

			return $order;

		} catch (\Exception $e) {
			//throw $th;
			\Log::error($e->getMessage());
			\Log::error($e->getTraceAsString());

			\DB::rollback();
			logger("Failed Orders Create - {$e->getMessage()}");
		}
	}
}