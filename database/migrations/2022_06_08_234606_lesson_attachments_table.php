<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LessonAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('subject_lesson_id');
            $table->string('img_url')->nullable();
            $table->string('video_thumbnail_image')->nullable();
            $table->string('origin_video_url')->nullable();
            $table->string('video_resize_480')->nullable();
            $table->string('video_resize_720')->nullable();
            $table->string('video_resize_1080')->nullable();
            $table->tinyInteger('type')->default(1)->comment("1:subject,2:lesson");
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
        Schema::dropIfExists('lesson_attachments');
    }
}
