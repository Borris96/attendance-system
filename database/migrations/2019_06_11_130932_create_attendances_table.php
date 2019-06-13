<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('staff_id');
            $table->string('workday_type')->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->unsignedInteger('month')->nullable();
            $table->unsignedInteger('date')->nullable();
            $table->string('day')->nullable();
            $table->time('should_work_time')->nullable();
            $table->time('should_home_time')->nullable();
            $table->time('actual_work_time')->nullable();
            $table->time('actual_home_time')->nullable();
            $table->unsignedInteger('extra_work_id')->nullable();
            $table->unsignedInteger('absence_id')->nullable();
            $table->integer('late_work')->nullable();
            $table->boolean('is_late')->nullable();
            $table->integer('early_home')->nullable();
            $table->boolean('is_early')->nullable();
            $table->decimal('should_duration',5,2)->nullable();
            $table->decimal('actual_duration',5,2)->nullable();
            $table->boolean('abnormal')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
