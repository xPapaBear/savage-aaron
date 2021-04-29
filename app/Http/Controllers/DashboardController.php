<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Multiplier;
use Illuminate\Http\Request;
use App\Actions\CreateOrderAction;
use App\Actions\CreateOrUpdateCustomerAction;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(CreateOrUpdateCustomerAction $createUpdateCustomer, CreateOrderAction $createOrder) {
        $shop = $this->checkAuth();
        if ( ! $shop ) dd('Busted');
        /* $customers = $shop->customers()->orderBy('id', 'DESC')->limit(5)->get();
        $customers->sortByDesc('total_points'); */
        $multipliers = Multiplier::orderBy( 'id', 'DESC' )->limit(5)->get();
        $customers = $shop->customers()->with(['orders', 'entries'])->limit(5)->get();
        $temp = $shop->customers()->with(['orders', 'entries'])->get()->toArray();
        $notSort = $temp;

        usort( $temp, function ( $a, $b ) {
            return $a['total_points'] <=> $b['total_points'];
        } );

        $temp = array_reverse( array_slice( $temp, 0, 5, true) );

        // $orders = $shop->api()->request(
        //     'GET',
        //     '/admin/api/orders.json',
        //     ['status' => 'open']
        // )['body']['orders'] ?? false;

        // if ( isset($orders) ) {
        //     foreach ($orders as $key => $order) {
        //         $customer = $createUpdateCustomer->execute($shop->name, $order->customer);
        //         $createOrder->execute($shop->name, $order, $customer);
        //     }
        // }

        $data = [
            'customers' => $customers,
            'multipliers' => $multipliers,
            'temp' => $temp,
            'notSort' => $notSort
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get Top 5 Customer based on Entry Points
     * EP = Entry Points
     */
    public function topFiveCustomerEP( Request $request ) {
        $shop = $this->checkAuth();

        $customers = Customer::get()->sortBy( 'total_points' )->toArray();

        return response()->json([
            'success' => true,
            'message' => __('messages.success'),
            'data' => array_values( $customers ) ?? []
        ]);
    }

    /**
     * Get Multiplier History Limit to latest 5
     */
    public function multiplierHistoryFive() {
        $shop = $this->checkAuth();
        
        $multiplierHistory = Multiplier::orderBy( 'id', 'DESC' )->limit( 5 )->get();

        return response()->json([
            'success' => true,
            'message' => __('messages.success'),
            'data' => $multiplierHistory
        ]);
    }
}
