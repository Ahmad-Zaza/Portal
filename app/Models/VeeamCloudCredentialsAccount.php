<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeeamCloudCredentialsAccount extends Model
{
    protected $table = 'veeam_cloud_credentials_accounts';
    public $timestamps  = true;
    protected $fillable = [
        "storage_account_id", "guid", "name", "type"
    ];

    public function storage_account(){
        return $this->belongsTo(AzureStorageAccount::class,'storage_account_id');
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
