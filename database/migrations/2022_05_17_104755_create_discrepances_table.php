<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscrepancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discrepances', function (Blueprint $table) {
            $table->id();

            $table->string('reference');
            $table->string('center');
            $table->string('offer_price');
            $table->string('offer_price_label');
            $table->string('price_label');

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
        Schema::dropIfExists('discrepances');
    }
}
