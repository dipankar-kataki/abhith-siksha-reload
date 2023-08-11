<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAddonsIdToCartOrOrderAssignSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_or_order_assign_subjects', function (Blueprint $table) {
            $table->longText('addons_id')->after('assign_subject_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_or_order_assign_subjects', function (Blueprint $table) {
            //
        });
    }
}
