<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_times', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attendance_id')->nullable();
            $table->time('add_start_time')->nullable();
            $table->time('add_end_time')->nullable();
            $table->decimal('duration',5,2)->nullable();
            $table->text('reason',140)->nullable();
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
        Schema::dropIfExists('add_times');
    }
}
