<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class veeamStorageObject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('veeam_storage_object', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string('folder_id');
            $table->foreign('folder_id')->references('id')->on('veeam_folder');
            $table->string('storage_object_id');
            $table->string('account_name');
            $table->string('account_id');
            $table->string('accountType');
            $table->string('description');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veeam_storage_object');
    }
}
