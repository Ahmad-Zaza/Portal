<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeeamBackupRepository extends Model
{
    public $table = 'veeam_backup_repositories';
    public $timestamps  = true;

    protected $fillable = [
        "object_storage_id", "proxy_id", "guid", "encryption_password_guid", "encryption_password_hint","name","display_name","cache_path","kind"
    ];

    public function object_storage(){
        return $this->belongsTo(VeeamObjectStorage::class,'object_storage_id');
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
