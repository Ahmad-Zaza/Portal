<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class azureStorageAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('azure_storage_account', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string('resource_group_id');
            //$table->foreign('resource_group_id')->references('id')->on('azure_resource_group');
            $table->string('storage_account_name');
            $table->string('kind');
            $table->string('location');
            $table->string('key_1');
            $table->string('key_2');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('azure_storage_account');
    }
}
