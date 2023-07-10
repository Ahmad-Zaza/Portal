<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacRestoreHistory extends Model
{
    protected $table       = 'bac_restore_history';
    public $timestamps  = true;
    protected $with  = ['details'];
    protected $fillable = [
        'organization_id','backup_job_id','restore_session_guid','name','restore_point_time','restore_point_type','is_restore_point_show_version','is_restore_point_show_deleted',
        'type', 'sub_type', 'options','items_count','status','request_time','completion_time','expiration_time'
    ];

    public function details()
    {
        return $this->hasMany(BacRestoreHistoryDetail::class, 'restore_history_id');
    }

    public function organization(){
        return $this->belongsTo(BacOrganization::class,'organization_id');
    }

    public function backup_job(){
        return $this->belongsTo(VeeamBackupJob::class,'backup_job_id');
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
