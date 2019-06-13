<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTotalAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('year')->nullable();
            $table->unsignedInteger('month')->nullable();
            $table->unsignedInteger('staff_id')->nullable();
            $table->decimal('total_should_duration',5,2)->nullable(); //
            $table->decimal('total_actual_duration',5,2)->nullable(); //
            $table->decimal('total_basic_duration',5,2)->nullable();
            $table->decimal('total_absence_duration',5,2)->nullable();
            $table->decimal('total_extra_work_duration',5,2)->nullable();
            $table->integer('total_late_work')->nullable(); //
            $table->integer('total_early_home')->nullable(); //
            $table->unsignedInteger('total_is_late')->nullable(); //
            $table->unsignedInteger('total_is_early')->nullable(); //
            $table->decimal('difference',5,2)->nullable();
            $table->boolean('abnormal')->nullable(); //
            $table->unsignedInteger('should_attend')->nullable(); //
            $table->unsignedInteger('actual_attend')->nullable(); //
            $table->decimal('salary',5,2)->nullable();
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
        Schema::dropIfExists('total_attendances');
    }
}
