<?php

namespace App\Http\Controllers;

use App\Models\Multiplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        
    }

    /**
     * Get Multiplier History Limit to latest 5
     */
    public function multiplierHistoryFive() {
        $shop = Auth::user();
        
        if ( $shop ) {
            $multiplierHistory = Multiplier::where( 'status', 0 )->orderBy( 'id', 'DESC' )->limit( 5 )->get();

            return response()->json([
                'success' => true,
                'message' => __('messages.success'),
                'data' => $multiplierHistory
            ]);
        }

        // Unathenticated
        return response()->json([
            'success' => false,
            'message' => __('messages.unathenticated')
        ], 401);
    }
}
