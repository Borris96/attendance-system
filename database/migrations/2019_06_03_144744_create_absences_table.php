<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('absence_type')->nullable();
            $table->unsignedInteger('staff_id');
            // $table->foreign('staff_id')->references('id')->on('staffs');
            $table->dateTime('absence_start_time')->nullable();
            $table->dateTime('absence_end_time')->nullable();
            $table->decimal('duration',5,2)->nullable();
            $table->boolean('approve')->nullable();
            $table->text('note',140)->nullable();
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
        Schema::dropIfExists('absences');
    }
}
