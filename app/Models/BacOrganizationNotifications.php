<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacOrganizationNotifications extends Model
{
    public $table       = 'bac_organization_notifications';
    public $timestamps  = true;

    protected $fillable = [
        "organization_id","notification_id","is_teams","is_email"
    ];

    public function notification(){
        return $this->belongsTo(BacNotifications::class,'notification_id');
    }

    public function organization(){
        return $this->belongsTo(BacOrganization::class,'organization_id');
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
