<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeeamObjectStorage extends Model
{
    protected $table = 'veeam_object_storages';
    public $timestamps  = true;
    protected $fillable = [
        "cloud_credentials_account_id", "guid", "name"
    ];

    public function cloud_credential_account(){
        return $this->belongsTo(VeeamCloudCredentialsAccount::class,'cloud_credentials_account_id');
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
