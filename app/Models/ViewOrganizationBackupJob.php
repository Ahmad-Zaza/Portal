<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewOrganizationBackupJob extends Model
{
    public $table = 'v_organization_backup_jobs';
    public $cols = [
        "organization_id",
        "storage_account_id","storage_account_name","backup_container","restore_container",
        "backup_job_kind",
        "repository_kind","repository_guid",
        "backup_job_id","backup_job_guid","backup_job_name","backup_job_kind","backup_job_display_name"
    ];
}
