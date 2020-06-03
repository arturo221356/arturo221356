<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIccDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icc_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('icc_id');
            $table->char('dn',10);
            $table->char('dn_temporal',10)->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('sub_product_id')->nullable();
            $table->integer('sold_price')->nullable();
            $table->integer('alta_status')->nullable();
            $table->boolean('exportado')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('icc_details');
    }
}
