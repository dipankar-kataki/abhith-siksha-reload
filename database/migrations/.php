<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPracticeTestAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_practice_test_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_practice_test_id')->nullable();
            $table->integer('question_id')->nullable();
            $table->integer('answer_id')->nullable();
            $table->integer('user_answer_id')->nullable();
            $table->integer('is_correct')->comment("0:incorrect,1:correct");
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
        Schema::dropIfExists('user_practice_test_answers');
    }
}
