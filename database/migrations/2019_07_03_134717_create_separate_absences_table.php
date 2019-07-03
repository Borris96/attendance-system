<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeparateAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('separate_absences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('absence_id')->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->unsignedInteger('month')->nullable();
            $table->unsignedInteger('date')->nullable();
            // $table->unsignedInteger('day')->nullable();
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
        Schema::dropIfExists('separate_absences');
    }
}
