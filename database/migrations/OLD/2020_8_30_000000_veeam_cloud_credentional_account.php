<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class veeamCloudCredentionalAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('veeam_cloud_credentional_account', function (Blueprint $table) {
            $table->increments("id");
            $table->string('azure_storage_account_id');
            //$table->foreign('azure_storage_account_id')->references('id')->on('azure_storage_account');
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
        Schema::dropIfExists('veeam_cloud_credentional_account');
    }
}
