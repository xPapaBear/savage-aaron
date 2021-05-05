<?php
namespace App\Actions;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CreateOrUpdateCustomerAction
{
	public function execute(string $shopDomain, object $data)
	{
		Log::info( '===== CLASS :: CreateOrUpdateCustomerAction START =====' );
		Log::info( '$data :: ' . json_encode( $data ) );

		$shop = User::where('name', $shopDomain)->first();

		$customer = Customer::updateOrCreate(
			[
				'store_customer_id' =>  $data->id,
				'email' => $data->email,
			],
			[
				'email' => $data->email,
				'first_name' => $data->first_name,
				'last_name' => $data->last_name,
				'phone' => $data->phone,
				'user_id' => $shop['id'],
				'raw' => json_encode($data)
			]
		);

		Log::info( '$customer :: ' . json_encode( $customer ) );
		Log::info( '===== CLASS :: CreateOrUpdateCustomerAction END =====' );

		return $customer;
	}
}