<?php

namespace App\Console\Commands;

use App\Http\Controllers\TestController;
use Illuminate\Console\Command;

class OrderPayment extends Command
{
    protected $testController;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order Payment manual testing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->testController = new TestController();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shopDomain = config( 'orderpayment.shopDomain' );
        $data = json_decode( json_encode ( config( 'orderpayment.data' ) ) );
        $customer = json_decode( json_encode ( config( 'orderpayment.customer' ) ) );

        $this->testController->testOrderPaidJob( $shopDomain, $data, $customer );
    }
}
