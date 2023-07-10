<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class VeeamBackupJob extends Model
{
    protected $table = 'veeam_backup_jobs';

    public $timestamps  = true;

    protected $fillable = [
        "backup_repository_id","guid","name","size","description"
    ];


    public function repositories(){
        return $this->belongsTo(VeeamBackupRepository::class,'backup_repository_id');
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
