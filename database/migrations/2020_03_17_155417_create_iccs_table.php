<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIccsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iccs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('icc', 20)->unique();
            $table->integer('icc_producto_id')->nullable();
            $table->integer('sucursal_id');
            $table->integer('icc_status_id');
            $table->char('dn',10)->nullable();    
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
        Schema::dropIfExists('iccs');
    }
}
