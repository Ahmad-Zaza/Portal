<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class azureContainer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('azure_container', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string('account_id');
            //$table->foreign('account_id')->references('account_id')->on('veeam_cloud_credentional_account');
            $table->string('container_name');
            $table->string('container_id');
            $table->string("region");
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
        Schema::dropIfExists('azure_container');
    }
}
