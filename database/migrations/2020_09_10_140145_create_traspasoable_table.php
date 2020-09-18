<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraspasoableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traspasoables', function (Blueprint $table) {
            $table->integer('traspaso_id');
            $table->integer('traspasoable_id');
            $table->string('traspasoable_type');
            $table->integer('old_inventario_id');
         
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traspasoable');
    }
}
