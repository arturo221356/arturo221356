<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('dn',10);
            $table->unsignedInteger('icc_id');
            $table->unsignedInteger('productoable_id');
            $table->string('productoable_type');
            $table->unsignedInteger('icc_product_id')->nullable();
            // $table->unsignedInteger('icc_sub_product_id')->nullable();
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
        Schema::dropIfExists('lineas');
    }
}
