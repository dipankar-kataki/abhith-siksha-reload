<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoThumbnailImageToLessonAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_attachments', function (Blueprint $table) {
            $table->string('video_thumbnail_image')->after('img_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_attachments', function (Blueprint $table) {
            $table->dropColumn('video_thumbnail_image');
        });
    }
}
