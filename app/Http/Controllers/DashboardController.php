<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Multiplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Claims\Custom;

class DashboardController extends Controller
{
    public function index() {
        
    }

    /**
     * Get Top 5 Customer based on Entry Points
     * EP = Entry Points
     */
    public function topFiveCustomerEP( Request $request ) {
        $shop = $this->checkAuth();

        $customers = Customer::get()->sortByDesc( 'total_points' )->toArray();

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
        
        $multiplierHistory = Multiplier::where( 'status', 0 )->orderBy( 'id', 'DESC' )->limit( 5 )->get();

        return response()->json([
            'success' => true,
            'message' => __('messages.success'),
            'data' => $multiplierHistory
        ]);
    }
}
