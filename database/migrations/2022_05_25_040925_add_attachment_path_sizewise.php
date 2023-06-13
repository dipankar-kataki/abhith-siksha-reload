<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentPathSizewise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_attachments', function (Blueprint $table) {
           $table->dropColumn('path');
           $table->dropColumn('size');
           $table->dropColumn('type');
           $table->string('img_url')->nullable();
           $table->string('origin_video_url')->nullable();
           $table->string('video_resize_480')->nullable();
           $table->string('video_resize_720')->nullable();
           $table->string('video_resize_1080')->nullable();
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
