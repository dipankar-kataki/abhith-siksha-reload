<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBoardIdCourseIdCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
          

            $table->unsignedBigInteger('chapter_id')->nullable()->change();
            $table->unsignedBigInteger('course_id')->nullable()->change();
            $table->dropForeign('carts_chapter_id_foreign');
            $table->foreign('chapter_id')
            ->references('id')
            ->on('chapters')
            ->nullOnDelete();
            $table->dropForeign('carts_course_id_foreign');
            $table->foreign('course_id')
            ->references('id')
            ->on('courses')
            ->nullOnDelete();
            $table->unsignedBigInteger('board_id')->after('chapter_id');
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
        Schema::table('carts', function (Blueprint $table) {
            
        });
    }
}
