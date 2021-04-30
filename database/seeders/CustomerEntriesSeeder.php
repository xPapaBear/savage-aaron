<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Multiplier;
Use App\Models\Order;
Use App\Models\Entry;
use Illuminate\Database\Seeder;

class CustomerEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $numbers = rand( 5, 15 );
        $order_id = 1;
        $multiplierIds = Multiplier::all()->pluck( 'id' )->toArray();

        for ( $i = 0; $i <= $numbers; $i++ ) {
            $email = $faker->safeEmail;
            $exist = Customer::where( 'email', $email )->first();

            if ( ! $exist ) {
                $customer = Customer::create([
                    'store_customer_id' => 1,
                    'email'     => $email,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'phone' => $faker->phoneNumber,
                    'user_id' => 1
                ]);

                $entry_count = rand( 1, 3 );

                for ( $x = 0; $x <= $entry_count; $x++ ) {
                    $multiplier_id = $multiplierIds[ array_rand( $multiplierIds ) ];
                    $total_price = rand( 150, 500 );

                    $order = Order::create([
                        'store_order_id' => $order_id++,
                        'user_id' => 1,
                        'customer_id' => $customer->id,
                        'total_line_items_price' => $total_price,
                    ]);
                
                    Entry::create([
                        'order_id' => $order->id,
                        'user_id' => 1,
                        'customer_id' => $customer->id,
                        'multiplier_id' => $multiplier_id,
                        'points' => rand( 10, 50 )
                    ]);
                }
            }
        }
    }
}
