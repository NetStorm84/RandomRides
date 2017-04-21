<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('routes', function (Blueprint $table) {
          $table->increments('id');
          $table->string('title');
          $table->decimal('bounds_sw_lng');
          $table->decimal('bounds_sw_lat');
          $table->decimal('bounds_ne_lng');
          $table->decimal('bounds_ne_lat');
          $table->float('distance');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
