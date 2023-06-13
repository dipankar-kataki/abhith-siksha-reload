<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUserDetailIdToKnowledgeForumPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knowledge_forum_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_detail_id')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knowledge_forum_posts', function (Blueprint $table) {
            $table->removeColumn('user_detail_id');
        });
    }
}
