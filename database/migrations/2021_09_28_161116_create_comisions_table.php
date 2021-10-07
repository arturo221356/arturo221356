<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisions', function (Blueprint $table) {
            $table->id();
            $table->decimal('porta', $precision = 8, $scale = 2)->default(0)->nullable();
            $table->decimal('n', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n1', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n2', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n3', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n4', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n5', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n6', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n7', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n8', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n9', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n10', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n11', $precision = 8, $scale = 2)->default(0);
            $table->decimal('n12', $precision = 8, $scale = 2)->default(0);
            $table->integer('comisionable_id');
            $table->text('comisionable_type');
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
        Schema::dropIfExists('comisions');
    }
}
