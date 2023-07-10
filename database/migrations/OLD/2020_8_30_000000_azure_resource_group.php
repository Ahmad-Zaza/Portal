<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class azureResourceGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('azure_resource_group', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string('organization_id');
            //$table->foreign('organization_id')->references('org_id')->on('organizations');
            $table->string('resource_group_name');
            $table->string('client_id');
            $table->string('secret_id');
            $table->string('subscription_id');
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
        Schema::dropIfExists('azure_resource_group');
    }
}
