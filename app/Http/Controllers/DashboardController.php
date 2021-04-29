<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Multiplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Claims\Custom;
use App\Actions\CreateOrderAction;
use App\Actions\CreateOrUpdateCustomerAction;

class DashboardController extends Controller
{
    public function index(CreateOrUpdateCustomerAction $createUpdateCustomer, CreateOrderAction $createOrder) {
        $shop = auth()->user();
        if ( ! $shop ) dd('Busted');
        /* $customers = $shop->customers()->orderBy('id', 'DESC')->limit(5)->get();
        $customers->sortByDesc('total_points'); */
        $multipliers = Multiplier::orderBy( 'id', 'DESC' )->limit(5)->get();
        $customers = $shop->customers()->with(['orders', 'entries']);

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
