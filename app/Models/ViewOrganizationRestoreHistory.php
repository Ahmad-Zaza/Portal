<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewOrganizationRestoreHistory extends Model
{
    public $table = 'v_organization_restore_history';
    public $with = ["details"];
    public $cols = [
        "id","name","restore_session_guid","restore_point_time","type","sub_type","items_count","status","request_time","completion_time","expiration_time",
        "organization_id",
        "backup_job_id","backup_job_guid","backup_job_name",
        "backup_job_kind","repository_display_name",
    ];
    public function details(){
        return $this->hasMany(BacRestoreHistoryDetail::class,"restore_history_id");
    }
}
