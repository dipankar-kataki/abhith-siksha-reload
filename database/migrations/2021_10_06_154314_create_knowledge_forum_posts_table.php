<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeForumPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_forum_posts', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('description');
            $table->text('links')->nullable();
            $table->unsignedBigInteger('total_comments')->default(0);
            $table->unsignedBigInteger('total_views')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_activate');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knowledge_forum_posts');
    }
}
