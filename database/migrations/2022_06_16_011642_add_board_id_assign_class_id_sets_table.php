<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBoardIdAssignClassIdSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->dropColumn('subject_id');
            $table->unsignedBigInteger('board_id')->after('set_name');
            $table->unsignedBigInteger('assign_class_id')->after('board_id');
            $table->unsignedBigInteger('assign_subject_id')->after('assign_class_id');
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->unsignedBigInteger('lesson_id')->after('assign_subject_id');
            $table->foreign('assign_class_id')->references('id')->on('assign_classes')->onDelete('cascade');
            $table->foreign('assign_subject_id')->references('id')->on('assign_subjects')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sets', function (Blueprint $table) {
            //
        });
    }
}
