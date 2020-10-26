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
            $table->char('temporal',10)->nullable();
            $table->boolean('trafico')->nullable();
            $table->boolean('trafico_real')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->dateTimeTz('preactivated_at')->nullable();
            $table->dateTimeTz('activated_at')->nullable();
            $table->dateTimeTz('fvc')->nullable();
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
