<?php
namespace App\Actions;

use App\Models\User;
use App\Models\Customer;

class CreateOrUpdateCustomerAction
{
	public function execute(string $shopDomain, object $data)
	{
		logger("Customer data =" .  json_encode($data));
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

		logger('Customer = ' . json_encode($customer));

		return $customer;
	}
}