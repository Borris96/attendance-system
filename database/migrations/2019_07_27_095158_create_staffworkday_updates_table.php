<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffworkdayUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffworkday_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('staffworkday_id');
            $table->unsignedInteger('staff_id');
            $table->string('workday_name',10);
            $table->boolean('is_work');
            $table->time('work_time')->nullable();
            $table->time('home_time')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('duration',5,2)->nullable();
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
        Schema::dropIfExists('staffworkday_updates');
    }
}
