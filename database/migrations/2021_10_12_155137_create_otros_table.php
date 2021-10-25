<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otros', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('distribution_id');
            $table->string('codigo')->nullable();
            $table->string('name');
            $table->string('description');
            $table->integer('precio');
            $table->integer('costo')->nullable();
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
        Schema::dropIfExists('otros');
    }
}
