<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blogs_id');
            $table->integer('report_count');
            $table->boolean('is_activate');
            $table->timestamps();


            $table->foreign('blogs_id')->references('id')->on('blogs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_blogs');
    }
}
