<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonAttachmentsTable extends Migration
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
            $table->integer('lesson_id');
            $table->string('original_name');
            $table->string('disk');
            $table->string('path');
            $table->tinyInteger('type')->comment("1:image,2:video");
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
