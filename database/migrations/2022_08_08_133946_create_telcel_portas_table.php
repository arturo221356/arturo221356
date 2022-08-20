<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelcelPortasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telcel_portas', function (Blueprint $table) {
            $table->id();
            $table->string('idcop')->nullable();
            $table->string('dn');
            $table->string('nip')->nullable();
            $table->string('imei')->nullable();
            $table->string('nombre');
            $table->string('apaterno');
            $table->string('amaterno');
            $table->string('curp');
            $table->string('pdv');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('linea_id')->nullable();
            $table->string('message')->nullable();
            $table->boolean('error')->default(false);
            $table->boolean('random_client')->default(false);
            $table->boolean('finnished')->default(false);
            $table->json('promociones')->nullable();
            $table->string('selected_promo')->nullable();
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
        Schema::dropIfExists('telcel_portas');
    }
}
