<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('teacher_id')->nullable();
            $table->unsignedInteger('lesson_id')->nullable();
            $table->unsignedInteger('term_id')->nullable();
            $table->date('lesson_date')->nullable();
            $table->date('alter_date')->nullable();
            $table->decimal('duration',5,1)->nullable();
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
        Schema::dropIfExists('alters');
    }
}
