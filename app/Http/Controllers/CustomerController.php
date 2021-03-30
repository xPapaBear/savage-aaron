<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user();

        if ( ! $shop ) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        $customers = $shop->customers()->with(['orders', 'entries'])->paginate(24);

        return response()->json([
            'success' => true,
            'data' => $customers
        ], 200);
    }

    public function filter(Request $request) {
        $shop = Auth::user();

        if ( ! $shop ) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        $customers = $shop->customers()->with(['orders' => function($q) use (&$request) {
            $q->filter($request);
        }, 'entries' => function($q) use (&$request) {
            $q->filter($request);
        }])->paginate(24);


        return response()->json([
            'success' => true,
            'data' => $customers,
        ], 200);
    }
}
