<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeForumCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_forum_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('knowledge_forum_post_id');
            $table->text('comments');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_activate');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('knowledge_forum_post_id')->references('id')->on('knowledge_forum_posts')->onDelete('cascade');
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
        Schema::dropIfExists('knowledge_forum_comments');
    }
}
