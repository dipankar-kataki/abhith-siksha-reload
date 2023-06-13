<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBoardClassIdToLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('board_id')->after('parent_id');
            $table->unsignedBigInteger('assign_class_id')->after('board_id');
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
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
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('board_id');
            $table->dropColumn('assign_class_id');
        });
    }
}
