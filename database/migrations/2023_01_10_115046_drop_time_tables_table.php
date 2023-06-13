<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTimeTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('time_tables');
        Schema::create('time_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id');
            $table->unsignedBigInteger('assign_class_id');
            $table->unsignedBigInteger('assign_subject_id');
            $table->string('time');
            $table->date('date');
            $table->string('zoom_link')->nullable();
            $table->integer('is_activate')->default(1)->comment("1:active,0:inactive");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('assign_subject_id')->references('id')->on('assign_subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
