<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileOrderRequest;
use App\Models\CustomerProfileOrder;
use App\Models\Multiplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProfilleOrderControler extends Controller
{
    public function getUserProfileOrders( Request $request, $id ) {
        $shop = $this->checkAuth();

        $profileOrder = CustomerProfileOrder::where( 'user_id', $id )->orderBy( 'created_at', 'DESC' )->get();

        return response()->json([
            'success' => true,
            'message' => __('messages.success'),
            'data' => $profileOrder
        ]);
    }

    public function storeProfileOrder( ProfileOrderRequest $request ) {
        $shop = $this->checkAuth();

        $multiplier = Multiplier::where('status', true)->first();
        $multiplier = $multiplier->value;
        $request->request->add( [ 'multiplier' => $multiplier ] );

        $profileOrder = CustomerProfileOrder::create( $request->all() );
        return response()->json([
            'success' => true,
            'message' => __('messages.success'),
            'data' => $profileOrder
        ]);
    }
}
