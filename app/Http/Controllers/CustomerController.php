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
    public function index() {
        $shop = $this->checkAuth();

        $customers = $shop->customers()->with(['orders', 'entries'])->paginate(25);

        return response()->json([
            'success' => true,
            'data' => $customers
        ], 200);
    }

    public function filter(Request $request) {
        $shop = $this->checkAuth();

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
