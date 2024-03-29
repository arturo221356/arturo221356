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
            $table->string('icc')->unique();
            $table->string('code')->nullable();
            $table->integer('inventario_id');
            $table->integer('company_id');
            $table->integer('icc_type_id');
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
