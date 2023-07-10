<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewOrganizationRepository extends Model
{
    public $table = 'v_organization_backup_repositories';
    public $cols = ["organization_id","repository_id","repository_guid","repository_name","backup_job_kind","repository_display_name"];
}
