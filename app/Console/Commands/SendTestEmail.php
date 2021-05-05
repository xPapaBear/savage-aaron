<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailEntry;
use App\Models\Customer;
use App\Models\Order;
use Tymon\JWTAuth\Claims\Custom;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:testmail {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Mail::to( $this->argument( 'email' ) )->send( new EmailEntry( 25, 5, 'Doncovish Dorvilma', 120, 120, 1547 ) );
        $customer = Customer::where( 'email', $this->argument( 'email' ) )->first();
        if ( $customer ) {
            $customer_id = $customer->id;
            $order = Order::where( 'customer_id', $customer_id )->orderBy( 'id', 'DESC' )->get();

            if ( count( $order ) > 0 ) {
                Mail::to( $this->argument( 'email' ) )->send( new EmailEntry( 25, 5, 'Doncovish Dorvilma', 120, 120, 1547 ) );
            }
        }
    }
}
