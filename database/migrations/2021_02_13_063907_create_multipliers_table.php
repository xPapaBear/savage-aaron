<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultipliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multipliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('value')->unsigned()->default(1);
            $table->string('label')->default('Entries');
            $table->unsignedTinyInteger('status')->unsigned()->default(1);
            $table->string('giveaway_start_date')->nullable();
            $table->string('giveaway_end_date')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
