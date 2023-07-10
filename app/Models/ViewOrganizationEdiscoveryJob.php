<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewOrganizationEdiscoveryJob extends Model
{
    public $table = 'v_organization_ediscovery_jobs';
    public $cols = [
        "id","name","restore_session_guid","restore_point_time","restore_point_type","is_restore_point_show_version","is_restore_point_show_deleted","duration","search_data","search_criteria","total_items","status","request_time","completion_time","expiration_time",
        "organization_id",
        "backup_job_id","backup_job_guid","backup_job_name",
        "backup_job_kind","repository_display_name",
    ];
}
