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
            $table->char('icc', 19)->unique();
            $table->unsignedInteger('sucursal_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('icc_type_id');
            // $table->foreign('sucursal_id')->references('id')->on('sucursales');  
            $table->integer('status_id'); 
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
