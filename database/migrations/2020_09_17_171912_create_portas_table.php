<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nip');
            $table->char('temporal',10);
            $table->boolean('trafico');
            $table->boolean('trafico_real');
            $table->date('fvc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portas');
    }
}
