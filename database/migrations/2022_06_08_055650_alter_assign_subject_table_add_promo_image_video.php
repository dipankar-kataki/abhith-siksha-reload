<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAssignSubjectTableAddPromoImageVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assign_subjects', function (Blueprint $table) {
            $table->longText('description')->after('is_activate')->nullable();
            $table->longText('why_learn')->after('description')->nullable();
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
        Schema::table('assign_subjects', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('why_learn');
        });
    }
}
