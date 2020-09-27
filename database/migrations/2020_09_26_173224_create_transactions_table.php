<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('taecel')->default(false);
            $table->boolean('taecel_success')->nullable();
            $table->string('taecel_transID')->nullable();
            $table->string('taecel_message')->nullable();
            $table->integer('monto');
            $table->string('dn');
            $table->integer('company_id');
            $table->integer('recarga_id');
            $table->integer('inventario_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
