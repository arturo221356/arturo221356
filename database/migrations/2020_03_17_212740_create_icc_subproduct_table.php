<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIccSubproductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icc_subproduct', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('recarga_required')->nullable();
            $table->integer('costo_sim')->nullable();
            $table->integer('pago_inicial')->nullable();
            $table->integer('total')->nullable();
            $table->integer('icc_product_id');
            // $table->unsignedBigInteger('icc_id');
            // $table->foreign('icc_id')->references('id')->on('iccs');
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
        Schema::dropIfExists('icc_subproduct');
    }
}
