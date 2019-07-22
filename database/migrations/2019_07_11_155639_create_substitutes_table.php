<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubstitutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('substitutes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lesson_id')->nullable();
            $table->unsignedInteger('teacher_id')->nullable(); // 原上课老师的id
            $table->unsignedInteger('substitute_teacher_id')->nullable(); // 代课老师的id, null说明缺课
            $table->unsignedInteger('term_id')->nullable();
            $table->decimal('duration',5,1)->nullable();
            $table->date('lesson_date');
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
        Schema::dropIfExists('substitutes');
    }
}
