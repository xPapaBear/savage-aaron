<?php
namespace App\Actions;

use App\Models\User;
use App\Models\Order;
use App\Mail\EmailEntry;
use App\Models\Customer;
use App\Models\Multiplier;
use Illuminate\Support\Arr;
use App\Actions\CreateEntryAction;
use Illuminate\Support\Facades\Mail;

class CreateOrderAction
{
	protected $createEntry;

	public function __construct() {
		$this->createEntry = new CreateEntryAction();
	}

	public function execute(string $shopDomain, object $data, object $customer)
	{
		\Log::info( '===== CLASS :: CreateOrderAction :: START =====' );
        \Log::info( '$shopDomain:: ' . $shopDomain );
        \Log::info( '$data:: ' . $data );
        \Log::info( '$customer:: ' . $customer );

		if ( isset($data) && empty($data) ) {

			\Log::info( '!! $data is not set !!' );
			return false;

		}

		try {
			\DB::beginTransaction();

			$shop = User::domain($shopDomain)->first();
			\Log::info( '$shop obj:: ' . $shop );

			// $multiplier = Multiplier::latest()
			// 	->whereDate('created_at', '<=', $data->created_at)
			// 	->first();

			$multiplier = Multiplier::whereDate('created_at', '<=', $data->created_at)
			->latest()
			->first();
			\Log::info( '$multiplier obj:: ' . $multiplier );

			// $multiplier = Multiplier::whereDate('created_at', '<=', $data->created_at)
			// ->orderBy('created_at', 'DESC')
			// ->first();

			// Log::info($multiplier);
							
			$totalGiftCardAmount = 0;

			$giftCards = Arr::where((array) $data->line_items, function ($item, $key) {
				return isset($item) && isset($item->gift_card) && $item->gift_card == true;
			});

			if (! empty($giftCards)) {
				foreach ($giftCards as $giftCard) {
					$totalGiftCardAmount += $giftCard->quantity * $giftCard->price;
				}
			}

			\Log::info( '$totalGiftCardAmount obj:: ' . $totalGiftCardAmount );
			\Log::info( '$giftCards obj:: ' . $giftCards );

			$totalPrice = $data->total_line_items_price - $totalGiftCardAmount;

			if ( isset($data) && isset($data->current_total_discounts) ) {
				$totalPrice = $totalPrice - $data->current_total_discounts;
			}
			\Log::info( '$totalPrice obj:: ' . $totalPrice );

			$points = $totalPrice * $multiplier->value;
			\Log::info( '$points obj:: ' . $points );

			$order = Order::updateOrCreate(
				['store_order_id' => $data->id],
				[
					'store_order_id' => $data->id,
					'order_number' => $data->order_number,
					'user_id' => $shop['id'],
					'customer_id' => $customer->id,
					'multiplier_id' => $multiplier->id,
					'total_line_items_price' => $totalPrice,
					'raw' => json_encode($data)
				]
			);
			\Log::info( '$order obj:: ' . $order );

			if ( $multiplier->status ) {
				\Log::info( '$multiplier->status obj:: ' . $multiplier->status );
				$entry = $this->createEntry->execute($shop, $order->id, $customer->id, $multiplier->id, $points);
				\Log::info( '$entry obj:: ' . $entry );
			}

			\DB::commit();

			// $exist = Order::where('store_order_id', $data->id)->first();

			// $tempOrder = Order::where('store_order_id', $data->id)->first();

			// logger('exist ' . $exist->id);

			$customer = Customer::find($customer->id);
			\Log::info( '$customer obj:: ' . $customer );

			if ( ! $order->is_email_sent ) {
				\Log::info( 'email sending' );

				// Email Contents
				$entry_points = $order->total_line_items_price * $multiplier->value;
				$customer_name = $customer->first_name;
				$order_cost  = $order->total_line_items_price;
				$entry_multiplier = $multiplier->value;
				$total_entry_points = $customer->total_points;
				$order_number = $order->order_number;
				$order_email = $customer->email;
				\Log::info( '### EMAIL TEMPLATE DATA START ###' );

				\Log::info( '$entry_points data:: ' . $entry_points );
				\Log::info( '$customer_name data:: ' . $customer_name );
				\Log::info( '$order_cost data:: ' . $order_cost );
				\Log::info( '$entry_multiplier data:: ' . $entry_multiplier );
				\Log::info( '$total_entry_points data:: ' . $total_entry_points );
				\Log::info( '$order_number data:: ' . $order_number );
				\Log::info( '$order_email data:: ' . $order_email );

				\Log::info( '### EMAIL TEMPLATE DATA END ###' );

				Mail::to($order_email)->send(new EmailEntry($order_cost, $entry_multiplier, $customer_name, $entry_points, $total_entry_points, $order_number));
				$order->is_email_sent = true;
				$order->save();
				\Log::info( '$order saved:: ' . $order );

			} else {
				\Log::info( 'email already sent' );
			}
			
			\Log::info( '$order obj:: ' . $order );
			return $order;

		} catch (\Exception $e) {
			//throw $th;
			\Log::error($e->getMessage());
			\Log::error($e->getTraceAsString());

			\DB::rollback();
			logger("Failed Orders Create - {$e->getMessage()}");
		}

        \Log::info( '===== CLASS :: CreateOrderAction :: END =====' );
	}
}