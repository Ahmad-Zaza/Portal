<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacOrganization extends Model
{
    protected $table = 'bac_organizations';

    protected $fillable = [
        'server_id','veeam_aad_authentication_guid','microsoft365_tenant_guid','microsoft365_tenant_name', 'veeam_organization_guid', 'veeam_organization_name',
        'azure_tenant_guid','azure_tenant_name','azure_subscription_guid','azure_resource_group','is_free_trial',
        'company_name','veeam_trial_users','veeam_licensed_users','total_license_allowed','license_alert_type','license_expiry_date',
        'notifications_users','registration_step',"marketplace_subscription_guid","marketplace_users_count",'status'
    ];

    public $timestamps  = true;

    public function server(){
        return $this->belongsTo(VeeamServer::class,"server_id");
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user = auth()->user();
            if($user){
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });
        static::updating(function ($model) {
            $user = auth()->user();
            if($user)
                $model->updated_by = $user->id;
        });
    }
}
