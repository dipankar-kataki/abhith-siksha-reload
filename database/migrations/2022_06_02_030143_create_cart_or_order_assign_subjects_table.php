<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartOrOrderAssignSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_or_order_assign_subjects', function (Blueprint $table) {
            $table->id();
            $table->integer('cart_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('assign_subject_id');
            $table->double('amount',8,2)->default(0);
            $table->tinyInteger('type')->comment("1:for cart,2:for order")->default(1);
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
        Schema::dropIfExists('cart_or_order_assign_subjects');
    }
}
