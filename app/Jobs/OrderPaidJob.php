<?php namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;
use App\Actions\CreateOrderAction;
use App\Actions\CreateOrUpdateCustomerAction;

class OrderPaidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain|string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param string   $shopDomain The shop's myshopify domain.
     * @param stdClass $data       The webhook data (JSON decoded).
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CreateOrUpdateCustomerAction $createUpdateCustomer, CreateOrderAction $createOrder)
    {
        $customer = $createUpdateCustomer->execute($this->shopDomain, $this->data->customer); // Create or update customer

        \Log::info( '===== CLASS :: OrderPaidJob :: START =====' );
        \Log::info( '$this->data:: ' . json_encode( $this->data ) );
        \Log::info( '$this->data->customer:: ' . json_encode( $this->data->customer ) );
        \Log::info( '$customer:: ' . json_encode( $customer ) );
        \Log::info( '===== CLASS :: OrderPaidJob :: END =====' );

        $createOrder->execute($this->shopDomain, $this->data, $customer); // create new order
    }
}
