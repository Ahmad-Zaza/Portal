<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AzureStorageAccount extends Model
{
    protected $table = 'azure_storage_accounts';

    public $timestamps = true;

    protected $fillable = [
        'organization_id', 'name', 'backup_container', 'restore_container',
    ];

    public function organization()
    {
        return $this->belongsTo(BacOrganization::class, "organization_id");
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
