<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelcelUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telcel_users', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('password');
            $table->string('internetpass')->nullable();
            $table->string('FzaVtaPrepagoPadre')->nullable();
            $table->string('FzaVtaPospagoPadre')->nullable();
            $table->string('FzaVtaPrepagoPersonal')->nullable();
            $table->string('FzaVtaPospagoPersonal')->nullable();
            $table->string('FzaVtaPrepagoReporte')->nullable();
            $table->string('FzaVtaPospagoReporte')->nullable();
            $table->string('idSesion')->nullable();
            $table->string('idDispositivo');
            $table->boolean('error')->nullable();
            $table->string('mensaje')->nullable();
            $table->string('distribution_id')->nullable();
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
        Schema::dropIfExists('telcel_users');
    }
}
