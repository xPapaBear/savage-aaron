<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerProfileOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_profile_orders', function (Blueprint $table) {
            $table->id();
            $table->integer( 'user_id' );
            $table->integer( 'order_id' );
            $table->float( 'total_items_price' );
            $table->integer( 'multiplier' );
            $table->integer( 'total_entry_points' );
            $table->json( 'metadata' )->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_profile_orders');
    }
}
