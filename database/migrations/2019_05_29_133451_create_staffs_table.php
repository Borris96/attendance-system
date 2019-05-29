<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique();
            $table->string('staffname',50);
            $table->string('englishname',50)->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('position_id')->nullable();
            $table->date('join_company')->nullable();
            $table->date('join_work')->nullable();
            $table->time('work_time');
            $table->time('home_time');
            $table->string('workdays',100); // Mon,Tue,Wed,Thu,Fri
            $table->decimal('annual_holiday',5,2)->nullable();
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staffs');
    }
}
