<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeacherDetailsColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('gender');
            $table->integer('total_experience_year')->after('dob')->nullable();
            $table->integer('total_experience_month')->nullable(); 
            $table->integer('assign_board_id')->nullable();
            $table->integer('assign_class_id')->nullable();
            $table->integer('assign_subject_id')->nullable();
            $table->double('hslc_percentage',8,2)->nullable();
            $table->double('hs_percentage',8,2)->nullable();
            $table->string('current_organization')->nullable();
            $table->string('current_designation')->nullable();
            $table->double('current_ctc',8,2)->nullable();
            $table->string('resume_url')->nullable();
            $table->string('teacherdemovideo_url')->nullable();
            $table->integer('status')->default('0')->comment("1:apply,2:approved,3:not_approved");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn('dob');
            $table->dropColumn('total_experience_year');
            $table->dropColumn('total_experience_month');
            $table->dropColumn('assign_board_id');
            $table->dropColumn('assign_class_id');
            $table->dropColumn('assign_subject_id');
            $table->dropColumn('hslc_percentage');
            $table->dropColumn('hs_percentage');
            $table->dropColumn('current_organization');
            $table->dropColumn('current_designation');
            $table->dropColumn('current_ctc');
            $table->dropColumn('resume_url');
            $table->dropColumn('teacherdemovideo_url');
        });
    }
}
