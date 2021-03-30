<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function index() {
        // Get the token
        $shop = auth()->user();

        if ( ! $shop ) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        $shopApi = $shop->api()->rest('GET', '/admin/shop.json')['body']['shop'];

        return response()->json([
            'success' => true,
            'data' => [
                'shop_api' => $shopApi,
                'shop' => $shop
            ]
        ], 200);

		

    }
}
