<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIccSubProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icc_sub_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('icc_product_id');
            $table->integer('precio');
            $table->integer('recarga_requerida')->nullable();
            $table->integer('recarga_externa')->nullable();
            $table->integer('costo_sim')->default(0);
            $table->integer('precio_inicial')->default(0);
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
        Schema::dropIfExists('icc_sub_products');
    }
}
