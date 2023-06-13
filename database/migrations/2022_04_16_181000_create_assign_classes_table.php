<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_classes', function (Blueprint $table) {
            $table->id();
            $table->string('class');
            $table->unsignedBigInteger('board_id');
            $table->boolean('is_activate')->default(1);
            $table->timestamps();

            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assign_classes');
    }
}
