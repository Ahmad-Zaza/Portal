<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string('name');
            $table->string('email');
            $table->string('domain_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('admin_name')->nullable();
            $table->boolean('subscription_type')->nullable();
            $table->string('microsoft_id')->nullable();
            $table->string('password');
            $table->integer('step');
            $table->string('portal_user_type')->default("organization_admin");
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
