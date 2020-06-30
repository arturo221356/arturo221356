<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIccProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icc_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('dn_required')->default(false);
            $table->boolean('dn_temporal_required')->default(false);
            $table->boolean('nip_required')->default(false);
            $table->boolean('recarga_required')->default(false);
            $table->boolean('costo_sim_required')->default(false);
            $table->boolean('initial_price_required')->default(false);
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
        Schema::dropIfExists('icc_products');
    }
}
