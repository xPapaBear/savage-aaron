<?php
namespace App\Actions;

use App\Models\Order;
use App\Models\User;
use App\Models\Multiplier;
use App\Actions\CreateEntryAction;

class CreateOrderAction
{
	protected $createEntry;

	public function __construct() {
		$this->createEntry = new CreateEntryAction();
	}

	public function execute(string $shopDomain, object $data, object $customer)
	{
		try {
			\DB::beginTransaction();

			$shop = User::domain($shopDomain)->first();

			$multiplier = Multiplier::latest()->first();
			logger('multipler' . $multiplier);


			$points = $data->total_line_items_price * $multiplier['value'];

			$order = Order::updateOrCreate(
				['store_order_id' => $data->id],
				[
					'store_order_id' => $data->id,
					'user_id' => $shop['id'],
					'customer_id' => $customer->id,
					'multiplier_id' => $multiplier->id,
					'total_line_items_price' => $data->total_line_items_price,
					'raw' => json_encode($data)
				]
			);

			if ( $multiplier->status ) {
				$entry = $this->createEntry->execute($shop->id, $order->id, $customer->id);
				logger('Entry' . $entry);
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