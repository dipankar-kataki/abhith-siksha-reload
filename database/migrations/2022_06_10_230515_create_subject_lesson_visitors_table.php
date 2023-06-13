<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectLessonVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_lesson_visitors', function (Blueprint $table) {
            $table->id();
            $table->integer('lesson_subject_id');
            $table->integer('teacher_id')->nullable();
            $table->integer('visitor_id');
            $table->integer('total_visit');
            $table->tinyInteger('type')->comment("1:subject,2:lesson");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_lesson_visitors');
    }
}
