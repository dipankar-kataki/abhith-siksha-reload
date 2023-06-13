<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileAndEmailVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_and_email_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_email_otp')->nullable();
            $table->integer('mobile_email_verification')->comment("1:verified,0:notverified")->nullable();
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
        Schema::dropIfExists('mobile_and_email_verifications');
    }
}
