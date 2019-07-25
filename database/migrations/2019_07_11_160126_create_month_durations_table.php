<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthDurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('month_durations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('year')->nullable();
            $table->unsignedInteger('month')->nullable();
            $table->unsignedInteger('term_id')->nullable();
            $table->unsignedInteger('teacher_id')->nullable();
            // 实际排课由这三个数据算出
            $table->decimal('fri_duration',5,1)->nullable();
            $table->decimal('sat_duration',5,1)->nullable();
            $table->decimal('sun_duration',5,1)->nullable();
            $table->decimal('mon_duration',5,1)->nullable();
            $table->decimal('wed_duration',5,1)->nullable();
            $table->decimal('other_duration',5,1)->nullable();
            $table->decimal('should_duration',5,1)->nullable(); // 应排课
            $table->decimal('actual_duration',5,1)->nullable(); // 实际上课，缺少的课时由 应排课-实际上课 得出
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
        Schema::dropIfExists('month_durations');
    }
}
