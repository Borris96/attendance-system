<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_works', function (Blueprint $table) {
            $table->increments('id');
            $table->string('extra_work_type')->nullable();
            $table->unsignedInteger('staff_id');
            $table->dateTime('extra_work_start_time')->nullable();
            $table->dateTime('extra_work_end_time')->nullable();
            $table->decimal('duration',5,2)->nullable();
            $table->boolean('approve')->nullable();
            $table->text('note',140)->nullable();
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
        Schema::dropIfExists('extra_works');
    }
}
