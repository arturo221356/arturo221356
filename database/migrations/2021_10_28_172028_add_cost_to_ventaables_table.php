<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostToVentaablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventaables', function (Blueprint $table) {
            
            $table->decimal('cost', $precision = 8, $scale = 2)->default(0);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventaables', function (Blueprint $table) {
            Schema::dropIfExists('cost');
        });
    }
}
