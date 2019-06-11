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
            $table->string('work_day_type')->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->unsignedInteger('month')->nullable();
            $table->unsignedInteger('date')->nullable();
            $table->string('day')->nullable();
            $table->time('actual_work_time')->nullable();
            $table->time('actual_home_time')->nullable();
            $table->unsignedInteger('late_work')->nullable();
            $table->unsignedInteger('early_home')->nullable();
            $table->decimal('total_time',5,2)->nullable();
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
