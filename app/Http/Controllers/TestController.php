<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailEntry;
use App\Models\User;
use App\Models\Multiplier;
use Illuminate\Support\Arr;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Entry;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function testTopCustomerEP( Request $request ) {
        Mail::to( 'gapafex637@gridmire.com' )->send(new EmailEntry( 10, 10, 'TEST1 TEST1', 10, 10, 10 ));

        Mail::mailer('smtp')->to('gapafex637@gridmire.com')->send(new EmailEntry(10, 10, 'TEST2 TEST2', 10, 10, 10));
    }

    /**
     * @test OrderPaidJob.php
     */
    /* public function testOrderPaidJob( string $shopDomain, object $data, object $customer ) {
        $this->testCreateOrderActionExecute( $shopDomain, $data, $customer );
    } */

    /**
     * @test CreateOrderAction.php -> execute
     */
    /* public function testCreateOrderActionExecute( string $shopDomain, object $data, object $customer ) {
		if ( isset($data) && empty($data) ) {
			return false;
		}

		try {
			DB::beginTransaction();

			$shop = User::domain( $shopDomain )->first();

			$multiplier = Multiplier::whereDate( 'created_at', '<=', $data->created_at )->latest()->first();
			$totalGiftCardAmount = 0;

			$giftCards = Arr::where( ( array ) $data->line_items, function ( $item, $key ) {
				return isset ( $item ) && isset( $item->gift_card ) && $item->gift_card == true;
			} );

			if ( ! empty( $giftCards ) ) {
				foreach ( $giftCards as $giftCard ) {
					$totalGiftCardAmount += $giftCard->quantity * $giftCard->price;
				}
			}

			$totalPrice = $data->total_line_items_price - $totalGiftCardAmount;

			if ( isset( $data ) && isset( $data->current_total_discounts ) ) {
				$totalPrice = $totalPrice - $data->current_total_discounts;
			}

			$points = $totalPrice * $multiplier->value;

			$order = Order::updateOrCreate(
				[ 'store_order_id' => $data->id ],
				[
					'store_order_id' => $data->id,
					'order_number' => $data->order_number,
					'user_id' => $shop['id'],
					'customer_id' => $customer->id,
					'multiplier_id' => $multiplier->id,
					'total_line_items_price' => $totalPrice,
					'raw' => json_encode( $data )
				]
			);

			if ( $multiplier->status ) {
				$this->testCreateEntryExecute( $shop, $order->id, $customer->id, $multiplier->id, $points );
			}

			DB::commit();

			$customer = Customer::find( $customer->id );

			if ( ! $order->is_email_sent ) {
				$entry_points = $order->total_line_items_price * $multiplier->value;
				$customer_name = $customer->first_name;
				$order_cost  = $order->total_line_items_price;
				$entry_multiplier = $multiplier->value;
				$total_entry_points = $customer->total_points;
				$order_number = $order->order_number;
				$order_email = $customer->email;

				Mail::to( $order_email )->send( new EmailEntry($order_cost, $entry_multiplier, $customer_name, $entry_points, $total_entry_points, $order_number ) );
				$order->is_email_sent = true;
				$order->save();

			}
			
			return $order;

		} catch ( \Exception $e ) {
			Log::error( $e->getMessage() );
			Log::error( $e->getTraceAsString() );
			DB::rollback();
		}
    } */

    /**
     * @test CreateEntryAction.php -> execute
     */
    /* public function testCreateEntryExecute( $shop, $orderId, $storeCustomerId, $multiplierId, $points = 0 ) {
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

			$user = User::where( 'id', $shop->id )->first();
			Auth::login($user);
			$shop = Auth::user();

			$shop->api()->request(
				'POST',
				"/admin/api/2020-10/customers/{$customer->store_customer_id}/metafields.json",
				['metafield' => [
					"namespace" => "giveaway",
					"key" => "entries",
					"value" => $totalEntries,
					"value_type" => "integer"
				]]
			);
			return $entry;

		} catch ( \Exception $e ) {
			Log::error( $e->getMessage() );
			Log::error( $e->getTraceAsString() );
		}
    } */
}
