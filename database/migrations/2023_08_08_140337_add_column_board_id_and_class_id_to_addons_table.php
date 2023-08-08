<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBoardIdAndClassIdToAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->string('board_id')->after('subject_id')->nullable();
            $table->string('class_id')->after('board_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addons', function (Blueprint $table) {
            //
        });
    }
}
