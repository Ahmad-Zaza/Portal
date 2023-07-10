<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacTempBlobFile extends Model
{
    protected $table       = 'bac_temp_blob_files';
    public $timestamps  = true;
    protected $fillable = ['storage_account_id','file_name','expiration_time'];

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
