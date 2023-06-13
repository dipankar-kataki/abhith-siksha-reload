<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_name');
            $table->unsignedBigInteger('assign_class_id');
            $table->boolean('is_activate')->default(1);
            $table->timestamps();

            $table->foreign('assign_class_id')->references('id')->on('assign_classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assign_subjects');
    }
}
