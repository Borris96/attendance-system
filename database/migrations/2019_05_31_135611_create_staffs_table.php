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
            $table->unsignedInteger('id');
            $table->string('staffname',50);
            $table->string('englishname',50)->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->string('department_name',50)->nullable();
            $table->unsignedInteger('position_id')->nullable();
            $table->string('position_name')->nullable();
            $table->date('join_company')->nullable();
            $table->date('leave_company')->nullable();
            $table->decimal('origin_work_year',5,2)->nullable(); // 只是之前参加工作的年数
            $table->decimal('work_year',5,2)->nullable(); // 总的参加工作年数
            $table->decimal('annual_holiday',5,2)->nullable();
            $table->decimal('remaining_annual_holiday',5,2)->nullable();
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
