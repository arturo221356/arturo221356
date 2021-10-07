<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivatedAtToRemplazosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remplazos', function (Blueprint $table) {
            $table->dateTimeTz('preactivated_at')->nullable();
            $table->dateTimeTz('activated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remplazos', function (Blueprint $table) {
            $table->dropColumn('preactivated_at');
            $table->dropColumn('activated_at');
        });
    }
}
