<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentPath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_attachments', function (Blueprint $table) {
            $table->dropColumn('original_name');
            $table->dropColumn('disk');
            $table->string('size')->after('path')->nullable();
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
            $table->string('original_name');
            $table->string('disk');
            $table->dropColumn('size');
        });
    }
}
