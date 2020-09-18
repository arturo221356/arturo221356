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
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('distribution_id');
            $table->unsignedInteger('icc_product_id');            
            $table->boolean('recarga_required')->default(false);
            $table->unsignedInteger('recarga_id')->nullable();
            $table->integer('precio')->default(0);
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
