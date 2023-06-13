<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLessonAttachmentAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_attachments', function (Blueprint $table) {
            $table->rename('assign_subject_id','lesson_id',);
            $table->tinyInteger('type')->comment("1:subject,2:lesson")->after('video_resize_1080');
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
            //
        });
    }
}
