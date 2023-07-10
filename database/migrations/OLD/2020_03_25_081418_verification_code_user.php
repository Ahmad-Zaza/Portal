<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VerificationCodeUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_code_users', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->integer('verification_codes_id')->unsigned();
            $table->foreign('verification_codes_id')->references('id')->on('verification_codes');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->date("end_date")->nullable();
            $table->decimal('license_allowed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_code_users');
    }
}
