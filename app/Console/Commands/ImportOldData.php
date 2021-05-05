<?php

namespace App\Console\Commands;

use App\Actions\CreateOrderAction;
use App\Actions\CreateOrUpdateCustomerAction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class ImportOldData extends Command
{
    protected $createUpdateCustomer;
    protected $createOrder;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:olddata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Old data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->createUpdateCustomer = new CreateOrUpdateCustomerAction();
        $this->createOrder = new CreateOrderAction();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shop = Auth::user();

        if ( $shop ) {
            $orders = $shop->api()->request(
                'GET',
                '/admin/api/orders.json',
                ['status' => 'open']
            )['body']['orders'] ?? false;

            if ( isset($orders) ) {
                foreach ($orders as $key => $order) {
                    $customer = $this->createUpdateCustomer->execute($shop->name, $order->customer);
                    $this->createOrder->execute($shop->name, $order, $customer);
                }
            }
        }
    }
}
