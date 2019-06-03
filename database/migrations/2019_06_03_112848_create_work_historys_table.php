<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_historys', function (Blueprint $table) {
            $table->increments('id');
            $table->date('work_experience')->nullable();
            $table->date('leave_experience')->nullable();
            $table->unsignedInteger('staff_id')->index();
            $table->foreign('staff_id')->references('id')->on('staffs');
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
        Schema::dropIfExists('work_historys');
    }
}
