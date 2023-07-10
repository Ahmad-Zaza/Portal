<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class veeamBackupRepository extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('veeam_backup_repository', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string('repository_id');
            $table->string('repository_name');
            $table->string('storage_object_id');
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
        Schema::dropIfExists('veeam_backup_repository');
    }
}
