<?php

namespace App\Console\Commands;

use App\Models\Entry;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteDuplicate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:duplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Duplicates';

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
        $datas = DB::table('orders')
            ->select('order_number', 'id', DB::raw('count(`order_number`) as `duplicates`'))
            ->groupBy('order_number')
            ->having('duplicates', '>', 1)
            ->get();

        Log::info( '===== DELETE DUPLICATE START =====' );

        if ( count( $datas ) > 0 ) {
            foreach ( $datas as $data ) {
                $order_to_delete = $data;
                $entry = Entry::where( 'order_id', $data->id )->first();
                
                if ( $entry ) {
                    Log::info( '$order :: ' . json_encode( $order_to_delete ) );
                    Log::info( '$entry :: ' . json_encode( $entry ) );

                    $order = Order::find( $order_to_delete->id );
                    $order->delete();
                    $entry->delete();
                }
            }
        }

        Log::info( '===== DELETE DUPLICATE END =====' );
    }
}
