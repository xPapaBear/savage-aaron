<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function getShopThemes($shop) {
		return $shop->api()->request(
			'GET',
			'/admin/api/themes.json'
		)['body']['themes'] ?? false;
	}

	public function checkAuth() {
		$shop = Auth::user();

        if ( ! $shop ) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthenticated')
            ], 401);
        }

		return $shop;
	}
}
