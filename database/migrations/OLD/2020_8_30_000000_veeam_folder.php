<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class veeamFolder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('veeam_folder', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string('account_id');
            //$table->foreign('account_id')->references('account_id')->on('veeam_cloud_credentional_account');
            $table->string('container_id');
            //$table->foreign('container_id')->references('container_id')->on('azure_container');
            $table->string('folder_name');
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
        Schema::dropIfExists('veeam_folder');
    }
}
